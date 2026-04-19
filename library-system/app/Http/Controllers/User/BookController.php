<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use App\Models\Borrow;
use App\Models\Reservation;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BookController extends Controller
{
    // =============================================
    // BOOK CATALOG
    // =============================================
    public function index(Request $request)
    {
        $categories = Category::withCount('books')->get();

        $books = Book::with('category')
            ->when($request->search, fn($q) => $q->search($request->search))
            ->when($request->category, fn($q) => $q->whereHas('category', fn($q2) => $q2->where('slug', $request->category)))
            ->when($request->availability === 'available', fn($q) => $q->available())
            ->when($request->sort === 'title', fn($q) => $q->orderBy('title'))
            ->when($request->sort === 'newest', fn($q) => $q->orderByDesc('created_at'))
            ->when($request->sort === 'popular', fn($q) => $q->withCount('borrows')->orderByDesc('borrows_count'))
            ->paginate(12)
            ->withQueryString();

        $userId = Auth::id();

        return view('user.books.index', compact('books', 'categories', 'userId'));
    }

    // =============================================
    // BOOK DETAIL
    // =============================================
    public function show(Book $book)
    {
        $book->load('category');
        $user = Auth::user();

        $isBorrowed    = $book->isBorrowedByUser($user->id);
        $isReserved    = $book->isReservedByUser($user->id);
        $activeBorrow  = Borrow::where('user_id', $user->id)->where('book_id', $book->id)
                                ->whereIn('status', ['active', 'overdue'])->first();
        $reservation   = Reservation::where('user_id', $user->id)->where('book_id', $book->id)
                                    ->whereIn('status', ['pending', 'ready'])->first();
        $queuePosition = Reservation::where('book_id', $book->id)
                                    ->whereIn('status', ['pending', 'ready'])->count() + 1;

        // Similar books
        $similar = Book::where('category_id', $book->category_id)
                       ->where('id', '!=', $book->id)
                       ->limit(4)->get();

        return view('user.books.show', compact(
            'book', 'isBorrowed', 'isReserved', 'activeBorrow',
            'reservation', 'queuePosition', 'similar'
        ));
    }

    // =============================================
    // RESERVE BOOK
    // =============================================
    public function reserve(Book $book)
    {
        $user = Auth::user();

        if (!$user->canBorrow()) {
            return back()->with('error', 'You cannot make reservations at this time. Check your account status or outstanding fines.');
        }

        if ($book->isReservedByUser($user->id) || $book->isBorrowedByUser($user->id)) {
            return back()->with('error', 'You already have this book reserved or borrowed.');
        }

        if (Reservation::where('user_id', $user->id)->whereIn('status', ['pending', 'ready'])->count() >= 3) {
            return back()->with('error', 'You can only have 3 active reservations at a time.');
        }

        DB::transaction(function () use ($book, $user) {
            $queuePos = Reservation::where('book_id', $book->id)
                                   ->whereIn('status', ['pending', 'ready'])->count() + 1;

            $reservation = Reservation::create([
                'reservation_code' => Reservation::generateCode(),
                'user_id'          => $user->id,
                'book_id'          => $book->id,
                'reserved_at'      => now(),
                'expires_at'       => now()->addHours(config('library.reservation_expiry_hours', 48)),
                'status'           => $book->isAvailable() ? 'ready' : 'pending',
                'queue_position'   => $queuePos,
            ]);

            if ($book->isAvailable()) {
                $book->decrement('available_copies');
                $book->increment('reserved_copies');
            }

            ActivityLog::log('reserve_book', "Reserved book: {$book->title} (#{$reservation->reservation_code})");
        });

        return back()->with('success', "Book \"{$book->title}\" has been reserved successfully!");
    }

    // =============================================
    // CANCEL RESERVATION
    // =============================================
    public function cancelReservation(Reservation $reservation)
    {
        if ($reservation->user_id !== Auth::id()) {
            abort(403);
        }

        DB::transaction(function () use ($reservation) {
            $book = $reservation->book;

            if ($reservation->status === 'ready') {
                $book->increment('available_copies');
                $book->decrement('reserved_copies');

                // Notify next in queue
                $next = Reservation::where('book_id', $book->id)
                                   ->where('status', 'pending')
                                   ->orderBy('created_at')
                                   ->first();
                if ($next) {
                    $next->update(['status' => 'ready', 'expires_at' => now()->addHours(48)]);
                    $book->decrement('available_copies');
                    $book->increment('reserved_copies');
                }
            }

            $reservation->update(['status' => 'cancelled']);
            ActivityLog::log('cancel_reservation', "Cancelled reservation for: {$book->title}");
        });

        return back()->with('success', 'Reservation cancelled.');
    }
}
