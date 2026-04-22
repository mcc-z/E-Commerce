@extends('layouts.app')
@section('title', 'My Fines')
@section('page-title', 'Fines & Payments')

@section('content')

@if($totalUnpaid > 0)
<div style="background:linear-gradient(135deg,#7f1d1d 0%,#dc2626 100%);border-radius:16px;padding:24px 28px;margin-bottom:24px;display:flex;align-items:center;justify-content:space-between;gap:20px;">
    <div>
        <div style="color:rgba(255,255,255,0.7);font-size:13px;margin-bottom:4px;">Total Outstanding</div>
        <div style="font-family:'DM Serif Display',serif;font-size:36px;color:white;line-height:1;">${{ number_format($totalUnpaid, 2) }}</div>
    </div>
    <div style="text-align:right;color:rgba(255,255,255,0.8);font-size:13px;">
        Please pay your fines to<br>continue borrowing books.
    </div>
</div>
@else
<div class="alert alert-success"><i class="fas fa-check-circle"></i> Your account is in good standing — no outstanding fines!</div>
@endif

<div class="card">
    <div class="card-header">
        <span class="card-title">All Fines</span>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Book</th>
                    <th>Type</th>
                    <th>Days Overdue</th>
                    <th>Total Fine</th>
                    <th>Paid</th>
                    <th>Remaining</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($fines as $fine)
                <tr>
                    <td>
                        <div style="font-weight:600;font-size:14px;">{{ $fine->borrow->book->title ?? 'N/A' }}</div>
                        <div style="font-size:12px;color:var(--text-muted);">{{ $fine->borrow->book->author ?? '' }}</div>
                    </td>
                    <td><span class="badge badge-secondary">{{ ucfirst($fine->type) }}</span></td>
                    <td style="font-size:13px;">{{ $fine->overdue_days > 0 ? $fine->overdue_days . ' days' : '—' }}</td>
                    <td style="font-weight:700;color:var(--danger);">${{ number_format($fine->amount, 2) }}</td>
                    <td style="color:var(--success);">${{ number_format($fine->paid_amount, 2) }}</td>
                    <td style="font-weight:700;">${{ number_format($fine->remaining, 2) }}</td>
                    <td><span class="badge {{ $fine->status_badge['class'] }}">{{ $fine->status_badge['label'] }}</span></td>
                    <td>
                        @if($fine->remaining > 0 && in_array($fine->status, ['unpaid','partial']))
                            <button class="btn btn-primary btn-xs" onclick="openPayModal({{ $fine->id }}, {{ $fine->remaining }})">
                                <i class="fas fa-credit-card"></i> Pay
                            </button>
                        @else
                            <span style="font-size:11px;color:var(--text-muted);">{{ ucfirst($fine->status) }}</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8">
                        <div class="empty-state">
                            <i class="fas fa-receipt"></i>
                            <h3>No fines on record</h3>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($fines->hasPages())
    <div style="padding:8px 16px 16px;">{{ $fines->links('pagination::simple-default') }}</div>
    @endif
</div>

{{-- Pay Modal --}}
<div id="payModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:1000;align-items:center;justify-content:center;">
    <div style="background:white;border-radius:16px;padding:32px;width:100%;max-width:400px;margin:20px;">
        <h3 style="font-family:'DM Serif Display',serif;font-size:22px;color:var(--navy);margin-bottom:6px;">Pay Fine</h3>
        <p style="color:var(--text-muted);font-size:13px;margin-bottom:24px;">Remaining balance: <strong id="modalRemaining"></strong></p>
        <form id="payForm" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Amount to Pay ($)</label>
                <input type="number" name="amount" id="payAmount" class="form-control no-icon" step="0.01" min="0.01" required>
            </div>
            <div class="form-group">
                <label class="form-label">Payment Method</label>
                <select name="method" class="form-control" style="padding:10px 14px;">
                    <option value="card">Credit / Debit Card</option>
                    <option value="online">Online Transfer</option>
                </select>
            </div>
            <div style="display:flex;gap:10px;margin-top:4px;">
                <button type="submit" class="btn btn-primary" style="flex:1;justify-content:center;">
                    <i class="fas fa-lock"></i> Pay Now
                </button>
                <button type="button" class="btn btn-outline" onclick="closePayModal()">Cancel</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function openPayModal(fineId, remaining) {
    document.getElementById('payForm').action = `/user/fines/${fineId}/pay`;
    document.getElementById('payAmount').max = remaining;
    document.getElementById('payAmount').value = remaining.toFixed(2);
    document.getElementById('modalRemaining').textContent = '$' + remaining.toFixed(2);
    document.getElementById('payModal').style.display = 'flex';
}
function closePayModal() {
    document.getElementById('payModal').style.display = 'none';
}
document.getElementById('payModal').addEventListener('click', function(e) {
    if (e.target === this) closePayModal();
});
</script>
@endpush
@endsection
