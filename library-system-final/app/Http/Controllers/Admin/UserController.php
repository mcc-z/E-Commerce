<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Borrow;
use App\Models\Fine;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::members()
            ->when($request->search, fn($q) => $q->where(function ($q2) use ($request) {
                $q2->where('name', 'like', "%{$request->search}%")
                   ->orWhere('email', 'like', "%{$request->search}%")
                   ->orWhere('member_id', 'like', "%{$request->search}%");
            }))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->withCount(['borrows', 'activeBorrows'])
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        $user->load([
            'borrows.book.category',
            'fines.borrow.book',
            'payments',
            'reservations.book',
        ]);

        $borrowStats = [
            'total'    => $user->borrows->count(),
            'active'   => $user->borrows->whereIn('status', ['active', 'overdue'])->count(),
            'overdue'  => $user->borrows->where('status', 'overdue')->count(),
            'returned' => $user->borrows->where('status', 'returned')->count(),
        ];

        return view('admin.users.show', compact('user', 'borrowStats'));
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name'    => 'required|string|max:100',
            'email'   => 'required|email|unique:users,email,' . $user->id,
            'phone'   => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'status'  => 'required|in:active,blocked,suspended',
        ]);

        if ($request->filled('password')) {
            $request->validate(['password' => 'min:8|confirmed']);
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);
        ActivityLog::log('update_user', "Admin updated user: {$user->name} ({$user->member_id})", $user);

        return back()->with('success', 'User updated successfully!');
    }

    public function toggleBlock(User $user)
    {
        if ($user->isAdmin()) {
            return back()->with('error', 'Cannot block an admin account.');
        }

        $newStatus = $user->status === 'blocked' ? 'active' : 'blocked';
        $user->update(['status' => $newStatus]);

        $action = $newStatus === 'blocked' ? 'blocked' : 'unblocked';
        ActivityLog::log("user_{$action}", "Admin {$action} user: {$user->name}", $user);

        return back()->with('success', "User has been {$action}.");
    }

    public function destroy(User $user)
    {
        if ($user->isAdmin()) {
            return back()->with('error', 'Cannot delete an admin account.');
        }
        if ($user->activeBorrows()->count() > 0) {
            return back()->with('error', 'Cannot delete user with active borrows.');
        }

        ActivityLog::log('delete_user', "Admin deleted user: {$user->name} ({$user->member_id})");
        $user->forceDelete();

        return redirect()->route('admin.users.index')->with('success', 'User deleted.');
    }

    // =============================================
    // ISSUE BORROW (Admin issues book to user)
    // =============================================
    public function issueBorrow(Request $request, User $user)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
        ]);

        if (!$user->canBorrow()) {
            return back()->with('error', 'User cannot borrow books at this time (blocked, fines outstanding, or max limit reached).');
        }

        $book = \App\Models\Book::findOrFail($request->book_id);

        if (!$book->isAvailable()) {
            return back()->with('error', 'This book is not available for borrowing.');
        }

        \Illuminate\Support\Facades\DB::transaction(function () use ($user, $book, $request) {
            $borrow = Borrow::create([
                'borrow_code' => Borrow::generateCode(),
                'user_id'     => $user->id,
                'book_id'     => $book->id,
                'borrowed_at' => now(),
                'due_date'    => now()->addDays(config('library.max_borrow_days', 14)),
                'status'      => 'active',
                'issued_by'   => auth()->id(),
                'notes'       => $request->notes,
            ]);

            $book->decrement('available_copies');
            $user->increment('total_borrowed');

            // Fulfil reservation if exists
            $res = \App\Models\Reservation::where('user_id', $user->id)
                    ->where('book_id', $book->id)
                    ->whereIn('status', ['pending', 'ready'])
                    ->first();
            if ($res) {
                $res->update(['status' => 'fulfilled']);
                $book->decrement('reserved_copies');
            }

            ActivityLog::log('issue_borrow', "Admin issued book \"{$book->title}\" to {$user->name}", $borrow);
        });

        return back()->with('success', "Book \"{$book->title}\" issued to {$user->name}.");
    }

    // =============================================
    // RETURN BOOK (Admin receives return)
    // =============================================
    public function returnBook(Borrow $borrow)
    {
        if (!in_array($borrow->status, ['active', 'overdue'])) {
            return back()->with('error', 'This borrow is already closed.');
        }

        \Illuminate\Support\Facades\DB::transaction(function () use ($borrow) {
            $fine = 0;
            if ($borrow->isOverdue()) {
                $fine = $borrow->calculateFine();
                Fine::create([
                    'user_id'      => $borrow->user_id,
                    'borrow_id'    => $borrow->id,
                    'type'         => 'overdue',
                    'overdue_days' => $borrow->overdue_days,
                    'amount'       => $fine,
                    'status'       => 'unpaid',
                ]);
                $borrow->user->increment('outstanding_fines', $fine);
            }

            $borrow->update([
                'status'      => 'returned',
                'returned_at' => now(),
                'returned_to' => auth()->id(),
            ]);

            $borrow->book->increment('available_copies');

            // Notify next reservation in queue
            $nextReservation = \App\Models\Reservation::where('book_id', $borrow->book_id)
                    ->where('status', 'pending')
                    ->orderBy('created_at')
                    ->first();
            if ($nextReservation) {
                $nextReservation->update(['status' => 'ready', 'expires_at' => now()->addHours(48)]);
                $borrow->book->decrement('available_copies');
                $borrow->book->increment('reserved_copies');
            }

            ActivityLog::log('return_book', "Book \"{$borrow->book->title}\" returned by {$borrow->user->name}" . ($fine > 0 ? " (Fine: \${$fine})" : ""), $borrow);
        });

        return back()->with('success', 'Book returned successfully!');
    }
}
