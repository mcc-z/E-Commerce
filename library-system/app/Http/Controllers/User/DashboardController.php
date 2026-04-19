<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Borrow;
use App\Models\Fine;
use App\Models\Reservation;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user()->load([
            'activeBorrows.book.category',
            'unpaidFines.borrow.book',
            'activeReservations.book',
        ]);

        // Stats
        $stats = [
            'active_borrows'    => $user->activeBorrows->count(),
            'overdue_count'     => $user->borrows()->where('status', 'overdue')->count(),
            'total_borrowed'    => $user->total_borrowed,
            'outstanding_fines' => $user->outstanding_fines,
            'reservations'      => $user->activeReservations->count(),
            'books_returned'    => $user->borrows()->where('status', 'returned')->count(),
        ];

        // Overdue borrows with fine calculation
        $overdueBorrows = Borrow::with('book')
            ->where('user_id', $user->id)
            ->where('status', 'overdue')
            ->get()
            ->map(function ($b) {
                $b->calculated_fine = $b->calculateFine();
                return $b;
            });

        // Recent activity (last 5 borrows)
        $recentActivity = Borrow::with('book')
            ->where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        // Recommended books (same category as last borrowed)
        $lastBorrow = Borrow::with('book.category')
            ->where('user_id', $user->id)
            ->latest()
            ->first();

        $recommended = Book::query()
            ->when($lastBorrow, fn($q) => $q->where('category_id', $lastBorrow->book->category_id))
            ->available()
            ->inRandomOrder()
            ->limit(4)
            ->get();

        return view('user.dashboard', compact(
            'user', 'stats', 'overdueBorrows',
            'recentActivity', 'recommended'
        ));
    }
}
