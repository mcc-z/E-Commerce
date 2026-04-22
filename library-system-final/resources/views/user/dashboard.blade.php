@extends('layouts.app')
@section('title', 'My Dashboard')
@section('page-title', 'My Dashboard')

@section('content')

{{-- Welcome banner --}}
<div style="background:linear-gradient(135deg,var(--navy) 0%,var(--navy-2) 100%);border-radius:18px;padding:28px 32px;margin-bottom:28px;display:flex;align-items:center;justify-content:space-between;gap:20px;">
    <div>
        <div style="color:rgba(255,255,255,0.6);font-size:13px;margin-bottom:4px;">Good {{ now()->hour < 12 ? 'morning' : (now()->hour < 18 ? 'afternoon' : 'evening') }},</div>
        <h2 style="font-family:'DM Serif Display',serif;color:white;font-size:26px;margin-bottom:6px;">{{ $user->name }}</h2>
        <div style="display:flex;align-items:center;gap:12px;">
            <span style="background:rgba(201,168,76,0.2);color:var(--gold-light);font-size:12px;padding:3px 10px;border-radius:20px;font-weight:600;">
                {{ $user->member_id }}
            </span>
            @if($user->outstanding_fines > 0)
                <span style="background:rgba(220,38,38,0.2);color:#fca5a5;font-size:12px;padding:3px 10px;border-radius:20px;font-weight:600;">
                    <i class="fas fa-exclamation-circle"></i> ${{ number_format($user->outstanding_fines, 2) }} outstanding
                </span>
            @endif
        </div>
    </div>
    <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}"
         style="width:72px;height:72px;border-radius:50%;object-fit:cover;border:3px solid var(--gold);flex-shrink:0;">
</div>

{{-- Stats --}}
<div class="stat-grid">
    <div class="stat-card">
        <div class="stat-icon blue"><i class="fas fa-book-reader"></i></div>
        <div>
            <div class="stat-value">{{ $stats['active_borrows'] }}</div>
            <div class="stat-label">Active Borrows</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon red"><i class="fas fa-clock"></i></div>
        <div>
            <div class="stat-value">{{ $stats['overdue_count'] }}</div>
            <div class="stat-label">Overdue Books</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon gold"><i class="fas fa-bookmark"></i></div>
        <div>
            <div class="stat-value">{{ $stats['reservations'] }}</div>
            <div class="stat-label">Reservations</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="fas fa-check-circle"></i></div>
        <div>
            <div class="stat-value">{{ $stats['books_returned'] }}</div>
            <div class="stat-label">Books Returned</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon red"><i class="fas fa-dollar-sign"></i></div>
        <div>
            <div class="stat-value">${{ number_format($stats['outstanding_fines'], 2) }}</div>
            <div class="stat-label">Outstanding Fines</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon purple"><i class="fas fa-history"></i></div>
        <div>
            <div class="stat-value">{{ $stats['total_borrowed'] }}</div>
            <div class="stat-label">Total Borrowed</div>
        </div>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;margin-bottom:24px;">

    {{-- Active Borrows --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title">Active Borrows</span>
            <a href="{{ route('user.borrows.index') }}" class="btn btn-outline btn-sm">View All</a>
        </div>
        <div class="card-body" style="padding-top:12px;">
            @forelse($user->activeBorrows as $borrow)
            <div style="display:flex;align-items:center;gap:12px;padding:10px 0;border-bottom:1px solid var(--cream-2);">
                <div style="width:40px;height:56px;background:var(--cream-2);border-radius:6px;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:18px;color:var(--text-muted);">
                    <i class="fas fa-book"></i>
                </div>
                <div style="flex:1;min-width:0;">
                    <div style="font-weight:600;font-size:14px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $borrow->book->title }}</div>
                    <div style="font-size:12px;color:var(--text-muted);">{{ $borrow->book->author }}</div>
                    <div style="font-size:11px;margin-top:3px;">
                        Due: <strong style="color:{{ $borrow->isOverdue() ? 'var(--danger)' : 'var(--navy)' }}">{{ $borrow->due_date->format('M d, Y') }}</strong>
                        @if($borrow->isOverdue())
                            <span class="badge badge-danger" style="margin-left:4px;">Overdue</span>
                        @endif
                    </div>
                </div>
                @if($borrow->canRenew())
                <form action="{{ route('user.borrows.renew', $borrow) }}" method="POST">
                    @csrf
                    <button class="btn btn-outline btn-xs">Renew</button>
                </form>
                @endif
            </div>
            @empty
            <div class="empty-state" style="padding:30px 0;">
                <i class="fas fa-book-open" style="font-size:32px;"></i>
                <p style="margin-top:8px;font-size:13px;">No active borrows</p>
                <a href="{{ route('user.books.index') }}" class="btn btn-primary btn-sm" style="margin-top:12px;">Browse Books</a>
            </div>
            @endforelse
        </div>
    </div>

    {{-- Overdue & Fines --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title">Outstanding Fines</span>
            <a href="{{ route('user.fines.index') }}" class="btn btn-outline btn-sm">Pay Fines</a>
        </div>
        <div class="card-body" style="padding-top:12px;">
            @forelse($user->unpaidFines as $fine)
            <div style="display:flex;align-items:center;justify-content:space-between;padding:10px 0;border-bottom:1px solid var(--cream-2);">
                <div>
                    <div style="font-size:14px;font-weight:600;">{{ $fine->borrow->book->title ?? 'Book' }}</div>
                    <div style="font-size:12px;color:var(--text-muted);">{{ $fine->overdue_days }} days overdue · {{ ucfirst($fine->type) }}</div>
                </div>
                <div style="text-align:right;">
                    <div style="font-size:15px;font-weight:700;color:var(--danger);">${{ number_format($fine->remaining, 2) }}</div>
                    <span class="badge {{ $fine->status_badge['class'] }}">{{ $fine->status_badge['label'] }}</span>
                </div>
            </div>
            @empty
            <div class="empty-state" style="padding:30px 0;">
                <i class="fas fa-check-circle" style="font-size:32px;color:var(--success);"></i>
                <p style="margin-top:8px;font-size:13px;color:var(--success);">No outstanding fines!</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

{{-- Recommended Books --}}
@if($recommended->count())
<div class="card">
    <div class="card-header">
        <span class="card-title">Recommended for You</span>
        <a href="{{ route('user.books.index') }}" class="btn btn-outline btn-sm">Browse All</a>
    </div>
    <div class="card-body">
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(160px,1fr));gap:16px;">
            @foreach($recommended as $book)
            <a href="{{ route('user.books.show', $book) }}" style="text-decoration:none;color:inherit;">
                <div style="background:var(--cream);border-radius:10px;overflow:hidden;transition:transform 0.2s,box-shadow 0.2s;" onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='0 8px 24px rgba(0,0,0,0.12)'" onmouseout="this.style.transform='';this.style.boxShadow=''">
                    <div style="height:110px;background:var(--navy-2);display:flex;align-items:center;justify-content:center;font-size:32px;color:rgba(255,255,255,0.2);">
                        <i class="fas fa-book"></i>
                    </div>
                    <div style="padding:10px;">
                        <div style="font-size:13px;font-weight:600;line-height:1.3;margin-bottom:3px;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">{{ $book->title }}</div>
                        <div style="font-size:11px;color:var(--text-muted);">{{ $book->author }}</div>
                        <div style="margin-top:6px;">
                            <span class="badge {{ $book->availability_badge['class'] }}" style="font-size:10px;">{{ $book->availability_badge['label'] }}</span>
                        </div>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</div>
@endif

@endsection
