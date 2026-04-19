@extends('layouts.app')
@section('title', 'Payments & Fines')
@section('page-title', 'Payments & Fines')

@section('content')

{{-- Revenue Stats --}}
<div class="stat-grid" style="grid-template-columns:repeat(4,1fr);margin-bottom:24px;">
    <div class="stat-card">
        <div class="stat-icon green"><i class="fas fa-calendar-day"></i></div>
        <div>
            <div class="stat-value">${{ number_format($stats['total_today'],2) }}</div>
            <div class="stat-label">Today's Revenue</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue"><i class="fas fa-calendar-alt"></i></div>
        <div>
            <div class="stat-value">${{ number_format($stats['total_month'],2) }}</div>
            <div class="stat-label">This Month</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon gold"><i class="fas fa-coins"></i></div>
        <div>
            <div class="stat-value">${{ number_format($stats['total_all'],2) }}</div>
            <div class="stat-label">All-Time Revenue</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon red"><i class="fas fa-exclamation-triangle"></i></div>
        <div>
            <div class="stat-value">${{ number_format($stats['unpaid_fines'],2) }}</div>
            <div class="stat-label">Unpaid Fines</div>
        </div>
    </div>
</div>

{{-- Filters --}}
<form method="GET" action="{{ route('admin.payments.index') }}">
    <div class="search-bar">
        <div class="search-input-wrap" style="flex:2">
            <i class="fas fa-search"></i>
            <input type="text" name="search" placeholder="Search payment code or member name..." value="{{ request('search') }}">
        </div>
        <select name="method" class="form-control" style="max-width:160px;padding:10px 14px;">
            <option value="">All Methods</option>
            <option value="cash"   {{ request('method')=='cash'   ? 'selected':'' }}>Cash</option>
            <option value="card"   {{ request('method')=='card'   ? 'selected':'' }}>Card</option>
            <option value="online" {{ request('method')=='online' ? 'selected':'' }}>Online</option>
            <option value="waived" {{ request('method')=='waived' ? 'selected':'' }}>Waived</option>
        </select>
        <button type="submit" class="btn btn-primary">Filter</button>
        @if(request()->anyFilled(['search','method']))
        <a href="{{ route('admin.payments.index') }}" class="btn btn-outline">Clear</a>
        @endif
    </div>
</form>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Member</th>
                    <th>Fine / Book</th>
                    <th>Amount</th>
                    <th>Method</th>
                    <th>Processed By</th>
                    <th>Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments as $payment)
                <tr>
                    <td style="font-family:monospace;font-size:12px;color:var(--text-muted);">{{ $payment->payment_code }}</td>
                    <td>
                        <a href="{{ route('admin.users.show', $payment->user) }}" style="text-decoration:none;color:var(--navy);font-weight:600;font-size:14px;">
                            {{ $payment->user->name }}
                        </a>
                        <div style="font-size:11px;color:var(--text-muted);">{{ $payment->user->member_id }}</div>
                    </td>
                    <td>
                        @if($payment->fine?->borrow?->book)
                            <div style="font-size:13px;">{{ $payment->fine->borrow->book->title }}</div>
                            <div style="font-size:11px;color:var(--text-muted);">{{ $payment->fine->overdue_days }}d overdue fine</div>
                        @else
                            <span style="color:var(--text-muted);font-size:13px;">—</span>
                        @endif
                    </td>
                    <td style="font-size:15px;font-weight:700;color:var(--success);">${{ number_format($payment->amount,2) }}</td>
                    <td>
                        @php
                            $methodIcon = match($payment->method) {
                                'cash'   => 'fa-money-bill',
                                'card'   => 'fa-credit-card',
                                'online' => 'fa-globe',
                                'waived' => 'fa-hand-holding-heart',
                                default  => 'fa-circle',
                            };
                        @endphp
                        <span style="display:inline-flex;align-items:center;gap:5px;font-size:13px;font-weight:500;">
                            <i class="fas {{ $methodIcon }}" style="color:var(--text-muted);"></i>
                            {{ ucfirst($payment->method) }}
                        </span>
                    </td>
                    <td style="font-size:13px;color:var(--text-muted);">
                        {{ $payment->processedBy?->name ?? 'Self-service' }}
                    </td>
                    <td style="font-size:12px;color:var(--text-muted);">{{ $payment->created_at->format('M d, Y H:i') }}</td>
                    <td>
                        <span class="badge {{ $payment->status === 'completed' ? 'badge-success' : 'badge-secondary' }}">
                            {{ ucfirst($payment->status) }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8"><div class="empty-state">No payments found.</div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($payments->hasPages())
    <div style="padding:8px 16px 16px;">{{ $payments->links('pagination::simple-default') }}</div>
    @endif
</div>
@endsection
