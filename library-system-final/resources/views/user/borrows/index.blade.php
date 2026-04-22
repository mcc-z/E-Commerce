@extends('layouts.app')
@section('title', 'My Borrows')
@section('page-title', 'My Borrows')

@section('content')

@if($overdueBorrows->count())
<div class="alert alert-error" style="margin-bottom:20px;">
    <i class="fas fa-exclamation-triangle"></i>
    <div>
        <strong>You have {{ $overdueBorrows->count() }} overdue book(s).</strong>
        Total estimated fine: ${{ number_format($overdueBorrows->sum('calculated_fine'), 2) }}
        <a href="{{ route('user.fines.index') }}" style="color:inherit;font-weight:700;"> — Pay Now →</a>
    </div>
</div>
@endif

<div class="card">
    <div class="card-header" style="padding-bottom:16px;">
        <span class="card-title">Borrow History</span>
        <div style="display:flex;gap:8px;">
            <span style="font-size:13px;color:var(--text-muted);">{{ $borrows->total() }} total records</span>
        </div>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Book</th>
                    <th>Borrowed</th>
                    <th>Due Date</th>
                    <th>Returned</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($borrows as $borrow)
                <tr>
                    <td style="font-family:monospace;font-size:12px;color:var(--text-muted);">{{ $borrow->borrow_code }}</td>
                    <td>
                        <div style="font-weight:600;font-size:14px;">{{ $borrow->book->title }}</div>
                        <div style="font-size:12px;color:var(--text-muted);">{{ $borrow->book->author }}</div>
                    </td>
                    <td style="font-size:13px;">{{ $borrow->borrowed_at->format('M d, Y') }}</td>
                    <td style="font-size:13px;font-weight:600;color:{{ $borrow->isOverdue() && !$borrow->returned_at ? 'var(--danger)' : 'var(--text)' }};">
                        {{ $borrow->due_date->format('M d, Y') }}
                        @if($borrow->isOverdue() && !$borrow->returned_at)
                            <div style="font-size:11px;color:var(--danger);">{{ $borrow->overdue_days }} days overdue</div>
                        @endif
                    </td>
                    <td style="font-size:13px;color:var(--text-muted);">
                        {{ $borrow->returned_at ? $borrow->returned_at->format('M d, Y') : '—' }}
                    </td>
                    <td>
                        <span class="badge {{ $borrow->status_badge['class'] }}">{{ $borrow->status_badge['label'] }}</span>
                    </td>
                    <td>
                        @if($borrow->canRenew())
                            <form action="{{ route('user.borrows.renew', $borrow) }}" method="POST" style="display:inline;">
                                @csrf
                                <button class="btn btn-success btn-xs">
                                    <i class="fas fa-redo"></i> Renew
                                </button>
                            </form>
                        @elseif(in_array($borrow->status, ['active','overdue']) && !$borrow->canRenew() && $borrow->renewal_count >= 2)
                            <span style="font-size:11px;color:var(--text-muted);">Max renewals</span>
                        @else
                            <span style="font-size:11px;color:var(--text-muted);">—</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7">
                        <div class="empty-state">
                            <i class="fas fa-book-reader"></i>
                            <h3>No borrows yet</h3>
                            <p>Start by browsing our book collection.</p>
                            <a href="{{ route('user.books.index') }}" class="btn btn-primary" style="margin-top:16px;">Browse Books</a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($borrows->hasPages())
    <div style="padding:8px 16px 16px;">
        {{ $borrows->links('pagination::simple-default') }}
    </div>
    @endif
</div>
@endsection
