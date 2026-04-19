<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Borrow;
use App\Models\Fine;
use App\Models\Payment;
use App\Models\Reservation;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Quick stats
        $stats = [
            'total_books'        => Book::count(),
            'total_members'      => User::members()->count(),
            'active_borrows'     => Borrow::whereIn('status', ['active', 'overdue'])->count(),
            'overdue_borrows'    => Borrow::where('status', 'overdue')->count(),
            'total_fines'        => Fine::whereIn('status', ['unpaid', 'partial'])->sum(DB::raw('amount - paid_amount')),
            'today_payments'     => Payment::whereDate('created_at', today())->sum('amount'),
            'available_books'    => Book::where('available_copies', '>', 0)->count(),
            'reservations'       => Reservation::whereIn('status', ['pending', 'ready'])->count(),
            'new_members_month'  => User::members()->whereMonth('created_at', now()->month)->count(),
            'blocked_users'      => User::blocked()->count(),
        ];

        // Monthly borrow trend (last 6 months)
        $borrowTrend = Borrow::select(
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"),
                DB::raw('COUNT(*) as total')
            )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Top borrowed books
        $topBooks = Book::withCount(['borrows' => fn($q) => $q->whereYear('created_at', now()->year)])
            ->orderByDesc('borrows_count')
            ->limit(5)
            ->get();

        // Recent overdue borrows
        $overdueBorrows = Borrow::with(['user', 'book'])
            ->where('status', 'overdue')
            ->orderBy('due_date')
            ->limit(8)
            ->get();

        // Recent activity
        $recentActivity = ActivityLog::with('user')
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        // Revenue by month (last 6 months)
        $revenueData = Payment::select(
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"),
                DB::raw('SUM(amount) as total')
            )
            ->where('status', 'completed')
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Category distribution
        $categoryStats = Book::with('category')
            ->select('category_id', DB::raw('COUNT(*) as count'), DB::raw('SUM(total_copies) as copies'))
            ->groupBy('category_id')
            ->with('category')
            ->get();

        return view('admin.dashboard', compact(
            'stats', 'borrowTrend', 'topBooks', 'overdueBorrows',
            'recentActivity', 'revenueData', 'categoryStats'
        ));
    }
}
