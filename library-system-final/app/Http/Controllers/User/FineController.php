<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Fine;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FineController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $fines = Fine::with(['borrow.book'])
            ->where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->paginate(10);

        $totalUnpaid = Fine::where('user_id', $user->id)
            ->whereIn('status', ['unpaid', 'partial'])
            ->sum(DB::raw('amount - paid_amount'));

        return view('user.fines.index', compact('fines', 'totalUnpaid'));
    }

    public function pay(Request $request, Fine $fine)
    {
        if ($fine->user_id !== Auth::id()) {
            abort(403);
        }

        if ($fine->remaining <= 0) {
            return back()->with('error', 'This fine is already paid.');
        }

        $request->validate([
            'amount' => "required|numeric|min:0.01|max:{$fine->remaining}",
            'method' => 'required|in:card,online',
        ]);

        DB::transaction(function () use ($request, $fine) {
            Payment::create([
                'payment_code' => Payment::generateCode(),
                'user_id' => $fine->user_id,
                'fine_id' => $fine->id,
                'amount' => $request->amount,
                'method' => $request->method,
                'status' => 'completed',
                'transaction_ref' => 'TXN-' . strtoupper(uniqid()),
            ]);

            $newPaid = $fine->paid_amount + $request->amount;
            $fine->update([
                'paid_amount' => $newPaid,
                'status' => $newPaid >= $fine->amount ? 'paid' : 'partial',
            ]);

            $fine->user->update([
                'outstanding_fines' => Fine::where('user_id', $fine->user_id)
                    ->whereIn('status', ['unpaid', 'partial'])
                    ->sum(DB::raw('amount - paid_amount')),
            ]);

            ActivityLog::log('pay_fine', "Paid \${$request->amount} for fine #{$fine->id} via {$request->method}");
        });

        return back()->with('success', 'Payment processed successfully!');
    }
}
