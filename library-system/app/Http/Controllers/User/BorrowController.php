<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Borrow;
use Illuminate\Support\Facades\Auth;

class BorrowController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $borrows = Borrow::with('book.category')
            ->where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->paginate(10);

        $overdueBorrows = Borrow::with(['book', 'fine'])
            ->where('user_id', $user->id)
            ->where('status', 'overdue')
            ->get();

        return view('user.borrows.index', compact('borrows', 'overdueBorrows'));
    }

    public function renew(Borrow $borrow)
    {
        if ($borrow->user_id !== Auth::id()) {
            abort(403);
        }

        if (! $borrow->canRenew()) {
            return back()->with('error', 'This borrow cannot be renewed. Max renewals reached or book is reserved by another user.');
        }

        $borrow->update([
            'due_date' => $borrow->due_date->addDays(14),
            'renewal_count' => $borrow->renewal_count + 1,
        ]);

        ActivityLog::log('renew_borrow', "Renewed borrow for: {$borrow->book->title} (#{$borrow->borrow_code})");

        return back()->with('success', "Borrow renewed! New due date: {$borrow->fresh()->due_date->format('M d, Y')}");
    }
}
