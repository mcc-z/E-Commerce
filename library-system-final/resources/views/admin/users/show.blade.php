@extends('layouts.app')
@section('title', $user->name)
@section('page-title', 'Member Profile')

@section('content')
<div class="page-header">
    <div style="display:flex;align-items:center;gap:16px;">
        <img src="{{ $user->avatar_url }}" style="width:56px;height:56px;border-radius:50%;object-fit:cover;border:2px solid var(--gold);">
        <div>
            <h1 style="font-size:22px;">{{ $user->name }}</h1>
            <div style="display:flex;align-items:center;gap:10px;margin-top:4px;">
                <span style="font-family:monospace;font-size:13px;color:var(--text-muted);">{{ $user->member_id }}</span>
                <span class="badge {{ $user->status_badge['class'] }}">{{ $user->status_badge['label'] }}</span>
            </div>
        </div>
    </div>
    <div style="display:flex;gap:8px;">
        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-outline btn-sm">
            <i class="fas fa-edit"></i> Edit
        </a>
        <form action="{{ route('admin.users.toggle-block', $user) }}" method="POST">
            @csrf
            <button class="btn btn-sm {{ $user->status === 'blocked' ? 'btn-success' : 'btn-warning' }}">
                <i class="fas {{ $user->status === 'blocked' ? 'fa-unlock' : 'fa-ban' }}"></i>
                {{ $user->status === 'blocked' ? 'Unblock' : 'Block' }} User
            </button>
        </form>
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline btn-sm">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
</div>

<div style="display:grid;grid-template-columns:280px 1fr;gap:24px;">

    {{-- Left: Info & Issue Book --}}
    <div>
        <div class="card" style="margin-bottom:16px;">
            <div class="card-body">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:16px;padding-bottom:16px;border-bottom:1px solid var(--cream-2);">
                    <div style="text-align:center;">
                        <div style="font-size:22px;font-weight:700;color:var(--navy);">{{ $borrowStats['total'] }}</div>
                        <div style="font-size:11px;color:var(--text-muted);text-transform:uppercase;">Total</div>
                    </div>
                    <div style="text-align:center;">
                        <div style="font-size:22px;font-weight:700;color:{{ $borrowStats['active'] > 0 ? 'var(--info)' : 'var(--text-muted)' }};">{{ $borrowStats['active'] }}</div>
                        <div style="font-size:11px;color:var(--text-muted);text-transform:uppercase;">Active</div>
                    </div>
                    <div style="text-align:center;">
                        <div style="font-size:22px;font-weight:700;color:{{ $borrowStats['overdue'] > 0 ? 'var(--danger)' : 'var(--text-muted)' }};">{{ $borrowStats['overdue'] }}</div>
                        <div style="font-size:11px;color:var(--text-muted);text-transform:uppercase;">Overdue</div>
                    </div>
                    <div style="text-align:center;">
                        <div style="font-size:22px;font-weight:700;color:var(--success);">{{ $borrowStats['returned'] }}</div>
                        <div style="font-size:11px;color:var(--text-muted);text-transform:uppercase;">Returned</div>
                    </div>
                </div>
                @foreach([
                    ['icon'=>'fa-envelope','label'=>'Email','value'=>$user->email],
                    ['icon'=>'fa-phone','label'=>'Phone','value'=>$user->phone ?? '—'],
                    ['icon'=>'fa-map-marker-alt','label'=>'Address','value'=>$user->address ?? '—'],
                    ['icon'=>'fa-calendar','label'=>'Joined','value'=>$user->created_at->format('M d, Y')],
                    ['icon'=>'fa-dollar-sign','label'=>'Outstanding Fines','value'=>'$'.number_format($user->outstanding_fines,2)],
                ] as $info)
                <div style="display:flex;gap:10px;margin-bottom:10px;font-size:13px;">
                    <i class="fas {{ $info['icon'] }}" style="width:16px;color:var(--text-muted);margin-top:2px;"></i>
                    <div>
                        <div style="color:var(--text-muted);font-size:11px;">{{ $info['label'] }}</div>
                        <div style="font-weight:500;">{{ $info['value'] }}</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Issue Book to User --}}
        @if($user->canBorrow())
        <div class="card">
            <div class="card-header"><span class="card-title" style="font-size:15px;">Issue Book</span></div>
            <div class="card-body">
                <form action="{{ route('admin.users.issue-borrow', $user) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Book ID</label>
                        <input type="number" name="book_id" class="form-control" placeholder="Enter Book ID" required>
                        <div style="font-size:11px;color:var(--text-muted);margin-top:4px;">Find the Book ID from the Books list.</div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="Optional notes..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-gold" style="width:100%;justify-content:center;">
                        <i class="fas fa-hand-holding-heart"></i> Issue Book
                    </button>
                </form>
            </div>
        </div>
        @else
        <div class="card">
            <div class="card-body" style="text-align:center;color:var(--danger);">
                <i class="fas fa-ban" style="font-size:24px;margin-bottom:8px;"></i>
                <div style="font-size:13px;font-weight:600;">Cannot Issue Books</div>
                <div style="font-size:12px;color:var(--text-muted);margin-top:4px;">
                    @if($user->isBlocked()) Account is blocked. @endif
                    @if($user->outstanding_fines > 10) Outstanding fines exceed $10. @endif
                    @if($user->activeBorrows->count() >= 5) Max borrow limit reached. @endif
                </div>
            </div>
        </div>
        @endif
    </div>

    {{-- Right: Borrows, Fines --}}
    <div>
        {{-- Active Borrows --}}
        <div class="card" style="margin-bottom:20px;">
            <div class="card-header"><span class="card-title">Active Borrows</span></div>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr><th>Book</th><th>Borrowed</th><th>Due Date</th><th>Status</th><th>Action</th></tr>
                    </thead>
                    <tbody>
                        @forelse($user->borrows->whereIn('status',['active','overdue']) as $borrow)
                        <tr>
                            <td>
                                <div style="font-weight:600;font-size:14px;">{{ $borrow->book->title }}</div>
                                <div style="font-size:12px;color:var(--text-muted);">{{ $borrow->borrow_code }}</div>
                            </td>
                            <td style="font-size:13px;">{{ $borrow->borrowed_at->format('M d, Y') }}</td>
                            <td style="font-size:13px;color:{{ $borrow->isOverdue() ? 'var(--danger)' : 'var(--text)' }};font-weight:{{ $borrow->isOverdue() ? '700' : '400' }};">
                                {{ $borrow->due_date->format('M d, Y') }}
                                @if($borrow->isOverdue())
                                    <div style="font-size:11px;">{{ $borrow->overdue_days }}d overdue</div>
                                @endif
                            </td>
                            <td><span class="badge {{ $borrow->status_badge['class'] }}">{{ $borrow->status_badge['label'] }}</span></td>
                            <td>
                                <form action="{{ route('admin.borrows.return', $borrow) }}" method="POST"
                                      onsubmit="return confirm('Mark this book as returned?')">
                                    @csrf
                                    <button class="btn btn-success btn-xs"><i class="fas fa-undo"></i> Return</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5"><div class="empty-state" style="padding:20px;">No active borrows.</div></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Fines --}}
        <div class="card">
            <div class="card-header"><span class="card-title">Fines</span></div>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr><th>Book</th><th>Type</th><th>Amount</th><th>Remaining</th><th>Status</th><th>Action</th></tr>
                    </thead>
                    <tbody>
                        @forelse($user->fines as $fine)
                        <tr>
                            <td style="font-size:13px;">{{ $fine->borrow->book->title ?? '—' }}</td>
                            <td><span class="badge badge-secondary">{{ ucfirst($fine->type) }}</span></td>
                            <td style="font-weight:700;">${{ number_format($fine->amount,2) }}</td>
                            <td style="font-weight:700;color:{{ $fine->remaining > 0 ? 'var(--danger)' : 'var(--success)' }}">
                                ${{ number_format($fine->remaining,2) }}
                            </td>
                            <td><span class="badge {{ $fine->status_badge['class'] }}">{{ $fine->status_badge['label'] }}</span></td>
                            <td>
                                @if($fine->remaining > 0)
                                    <div style="display:flex;gap:4px;">
                                        <button class="btn btn-success btn-xs" onclick="openPayModal({{ $fine->id }}, {{ $fine->remaining }})">
                                            <i class="fas fa-dollar-sign"></i> Pay
                                        </button>
                                        <button class="btn btn-outline btn-xs" onclick="openWaiveModal({{ $fine->id }})">
                                            Waive
                                        </button>
                                    </div>
                                @else
                                    <span style="font-size:11px;color:var(--text-muted);">Settled</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6"><div class="empty-state" style="padding:20px;">No fines on record.</div></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Record Payment Modal --}}
<div id="payModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:1000;align-items:center;justify-content:center;">
    <div style="background:white;border-radius:16px;padding:32px;max-width:380px;width:90%;margin:20px;">
        <h3 style="font-family:'DM Serif Display',serif;font-size:20px;color:var(--navy);margin-bottom:20px;">Record Payment</h3>
        <form id="payForm" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Amount ($) — max: <span id="maxAmount"></span></label>
                <input type="number" name="amount" id="payAmount" class="form-control" step="0.01" min="0.01" required>
            </div>
            <div class="form-group">
                <label class="form-label">Method</label>
                <select name="method" class="form-control" style="padding:10px 14px;">
                    <option value="cash">Cash</option>
                    <option value="card">Card</option>
                    <option value="online">Online</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Notes</label>
                <input type="text" name="notes" class="form-control" placeholder="Optional...">
            </div>
            <div style="display:flex;gap:10px;">
                <button type="submit" class="btn btn-success" style="flex:1;justify-content:center;">Record</button>
                <button type="button" class="btn btn-outline" onclick="closePayModal()">Cancel</button>
            </div>
        </form>
    </div>
</div>

{{-- Waive Fine Modal --}}
<div id="waiveModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:1000;align-items:center;justify-content:center;">
    <div style="background:white;border-radius:16px;padding:32px;max-width:380px;width:90%;margin:20px;">
        <h3 style="font-family:'DM Serif Display',serif;font-size:20px;color:var(--navy);margin-bottom:20px;">Waive Fine</h3>
        <form id="waiveForm" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Reason for Waiving *</label>
                <textarea name="reason" class="form-control" rows="3" required placeholder="Explain why this fine is being waived..."></textarea>
            </div>
            <div style="display:flex;gap:10px;">
                <button type="submit" class="btn btn-warning" style="flex:1;justify-content:center;">Waive Fine</button>
                <button type="button" class="btn btn-outline" onclick="closeWaiveModal()">Cancel</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function openPayModal(fineId, remaining) {
    document.getElementById('payForm').action = `/admin/fines/${fineId}/record-payment`;
    document.getElementById('payAmount').value = remaining.toFixed(2);
    document.getElementById('payAmount').max = remaining;
    document.getElementById('maxAmount').textContent = '$' + remaining.toFixed(2);
    document.getElementById('payModal').style.display = 'flex';
}
function closePayModal() { document.getElementById('payModal').style.display = 'none'; }

function openWaiveModal(fineId) {
    document.getElementById('waiveForm').action = `/admin/fines/${fineId}/waive`;
    document.getElementById('waiveModal').style.display = 'flex';
}
function closeWaiveModal() { document.getElementById('waiveModal').style.display = 'none'; }
</script>
@endpush
@endsection
