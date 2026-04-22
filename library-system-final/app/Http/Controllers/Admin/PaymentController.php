<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Fine;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $payments = Payment::with(['user', 'fine.borrow.book', 'processedBy'])
            ->when($request->search, fn($q) => $q->where(function ($q2) use ($request) {
                $q2->where('payment_code', 'like', "%{$request->search}%")
                   ->orWhereHas('user', fn($u) => $u->where('name', 'like', "%{$request->search}%"));
            }))
            ->when($request->method, fn($q) => $q->where('method', $request->method))
            ->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

        $stats = [
            'total_today' => Payment::whereDate('created_at', today())->sum('amount'),
            'total_month' => Payment::whereMonth('created_at', now()->month)->sum('amount'),
            'total_all' => Payment::where('status', 'completed')->sum('amount'),
            'unpaid_fines' => Fine::whereIn('status', ['unpaid', 'partial'])->sum(DB::raw('amount - paid_amount')),
        ];

        return view('admin.payments.index', compact('payments', 'stats'));
    }

    public function waiveFine(Request $request, Fine $fine)
    {
        $request->validate(['reason' => 'required|string|max:255']);

        DB::transaction(function () use ($request, $fine) {
            $remaining = $fine->remaining;

            $fine->update([
                'status' => 'waived',
                'paid_amount' => $fine->amount,
                'reason' => $request->reason,
            ]);

            $fine->user->update([
                'outstanding_fines' => Fine::where('user_id', $fine->user_id)
                    ->whereIn('status', ['unpaid', 'partial'])
                    ->sum(DB::raw('amount - paid_amount')),
            ]);

            Payment::create([
                'payment_code' => Payment::generateCode(),
                'user_id' => $fine->user_id,
                'fine_id' => $fine->id,
                'amount' => $remaining,
                'method' => 'waived',
                'status' => 'completed',
                'processed_by' => auth()->id(),
                'notes' => 'Waived by admin: ' . $request->reason,
            ]);

            ActivityLog::log('waive_fine', "Admin waived fine #{$fine->id} for user {$fine->user->name}. Reason: {$request->reason}", $fine);
        });

        return back()->with('success', 'Fine waived successfully.');
    }

    public function recordPayment(Request $request, Fine $fine)
    {
        $request->validate([
            'amount' => "required|numeric|min:0.01|max:{$fine->remaining}",
            'method' => 'required|in:cash,card,online',
            'notes' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request, $fine) {
            Payment::create([
                'payment_code' => Payment::generateCode(),
                'user_id' => $fine->user_id,
                'fine_id' => $fine->id,
                'amount' => $request->amount,
                'method' => $request->method,
                'status' => 'completed',
                'processed_by' => auth()->id(),
                'notes' => $request->notes,
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

            ActivityLog::log('record_payment', "Admin recorded \${$request->amount} payment for fine #{$fine->id}", $fine);
        });

        return back()->with('success', 'Payment recorded successfully!');
    }
}
