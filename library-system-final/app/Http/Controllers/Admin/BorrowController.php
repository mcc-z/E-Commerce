<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Borrow;
use Illuminate\Http\Request;

class BorrowController extends Controller
{
    public function index(Request $request)
    {
        $borrows = Borrow::with(['user', 'book.category'])
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->search, fn($q) => $q->where(function ($q2) use ($request) {
                $q2->whereHas('user', fn($u) => $u->where('name', 'like', "%{$request->search}%"))
                   ->orWhereHas('book', fn($b) => $b->where('title', 'like', "%{$request->search}%"))
                   ->orWhere('borrow_code', 'like', "%{$request->search}%");
            }))
            ->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

        $counts = [
            'active' => Borrow::where('status', 'active')->count(),
            'overdue' => Borrow::where('status', 'overdue')->count(),
            'returned' => Borrow::where('status', 'returned')->count(),
        ];

        return view('admin.borrows.index', compact('borrows', 'counts'));
    }

    public function updateOverdue()
    {
        $updated = Borrow::where('status', 'active')
            ->where('due_date', '<', now())
            ->update(['status' => 'overdue']);

        return back()->with('success', "{$updated} borrows marked as overdue.");
    }
}
