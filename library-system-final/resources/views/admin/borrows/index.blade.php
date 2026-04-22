@extends('layouts.app')
@section('title', 'Borrows')
@section('page-title', 'Borrows')

@section('content')

{{-- Status count tabs --}}
<div style="display:flex;gap:8px;margin-bottom:20px;flex-wrap:wrap;">
    @foreach([
        ['label'=>'All',      'value'=>'',         'count'=>array_sum($counts), 'color'=>'var(--navy)'],
        ['label'=>'Active',   'value'=>'active',   'count'=>$counts['active'],  'color'=>'var(--info)'],
        ['label'=>'Overdue',  'value'=>'overdue',  'count'=>$counts['overdue'], 'color'=>'var(--danger)'],
        ['label'=>'Returned', 'value'=>'returned', 'count'=>$counts['returned'],'color'=>'var(--success)'],
    ] as $tab)
    <a href="{{ route('admin.borrows.index', ['status'=>$tab['value']]) }}"
       style="padding:8px 16px;border-radius:8px;font-size:13px;font-weight:600;text-decoration:none;border:1.5px solid;transition:all 0.2s;
              {{ request('status') == $tab['value'] ? "background:{$tab['color']};color:white;border-color:{$tab['color']};" : 'background:white;color:var(--text-muted);border-color:var(--cream-2);' }}">
        {{ $tab['label'] }}
        <span style="margin-left:4px;background:rgba(255,255,255,0.25);padding:1px 6px;border-radius:10px;font-size:11px;">
            {{ $tab['count'] }}
        </span>
    </a>
    @endforeach

    <form action="{{ route('admin.borrows.update-overdue') }}" method="POST" style="margin-left:auto;">
        @csrf
        <button class="btn btn-outline btn-sm" title="Mark overdue borrows">
            <i class="fas fa-sync"></i> Refresh Overdue
        </button>
    </form>
</div>

<form method="GET" action="{{ route('admin.borrows.index') }}">
    <input type="hidden" name="status" value="{{ request('status') }}">
    <div class="search-bar">
        <div class="search-input-wrap" style="flex:2">
            <i class="fas fa-search"></i>
            <input type="text" name="search" placeholder="Search by borrow code, member, or book..." value="{{ request('search') }}">
        </div>
        <button type="submit" class="btn btn-primary">Search</button>
        @if(request('search'))
        <a href="{{ route('admin.borrows.index', ['status'=>request('status')]) }}" class="btn btn-outline">Clear</a>
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
                    <th>Book</th>
                    <th>Borrowed</th>
                    <th>Due Date</th>
                    <th>Returned</th>
                    <th>Status</th>
                    <th>Fine</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($borrows as $borrow)
                <tr>
                    <td style="font-family:monospace;font-size:12px;color:var(--text-muted);">{{ $borrow->borrow_code }}</td>
                    <td>
                        <a href="{{ route('admin.users.show', $borrow->user) }}" style="text-decoration:none;color:var(--navy);font-weight:600;font-size:14px;">
                            {{ $borrow->user->name }}
                        </a>
                        <div style="font-size:11px;color:var(--text-muted);">{{ $borrow->user->member_id }}</div>
                    </td>
                    <td>
                        <div style="font-size:14px;font-weight:500;">{{ $borrow->book->title }}</div>
                        <div style="font-size:12px;color:var(--text-muted);">{{ $borrow->book->author }}</div>
                    </td>
                    <td style="font-size:13px;">{{ $borrow->borrowed_at->format('M d, Y') }}</td>
                    <td style="font-size:13px;font-weight:{{ $borrow->isOverdue() && !$borrow->returned_at ? '700' : '400' }};color:{{ $borrow->isOverdue() && !$borrow->returned_at ? 'var(--danger)' : 'var(--text)' }};">
                        {{ $borrow->due_date->format('M d, Y') }}
                        @if($borrow->isOverdue() && !$borrow->returned_at)
                        <div style="font-size:11px;">{{ $borrow->overdue_days }}d overdue</div>
                        @endif
                    </td>
                    <td style="font-size:13px;color:var(--text-muted);">
                        {{ $borrow->returned_at ? $borrow->returned_at->format('M d, Y') : '—' }}
                    </td>
                    <td><span class="badge {{ $borrow->status_badge['class'] }}">{{ $borrow->status_badge['label'] }}</span></td>
                    <td>
                        @if($borrow->fine)
                            <span style="font-size:13px;font-weight:700;color:{{ $borrow->fine->remaining > 0 ? 'var(--danger)' : 'var(--success)' }}">
                                ${{ number_format($borrow->fine->amount, 2) }}
                            </span>
                            @if($borrow->fine->remaining > 0)
                                <div style="font-size:11px;color:var(--text-muted);">Rem: ${{ number_format($borrow->fine->remaining,2) }}</div>
                            @endif
                        @elseif($borrow->isOverdue() && !$borrow->returned_at)
                            <span style="font-size:12px;color:var(--warning);">~${{ number_format($borrow->calculateFine(),2) }}</span>
                        @else
                            <span style="color:var(--text-muted);">—</span>
                        @endif
                    </td>
                    <td>
                        @if(in_array($borrow->status, ['active','overdue']))
                        <button type="button" class="btn btn-success btn-xs" onclick="openReturnModal('{{ route('admin.borrows.return', $borrow) }}')"><i class="fas fa-undo"></i> Return</button>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="9"><div class="empty-state">No borrows found.</div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($borrows->hasPages())
    <div style="padding:8px 16px 16px;">{{ $borrows->links('pagination::simple-default') }}</div>
    @endif
</div>

<div id="returnModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:1000; align-items:center; justify-content:center;">
    <div style="background:white; border-radius:16px; padding:32px; max-width:400px; width:90%;">
        <h3 style="font-family:'DM Serif Display',serif; color:var(--navy); margin-bottom:12px;">Confirm Book Return</h3>
        <p style="color:var(--text-muted); margin-bottom:24px;">Confirm that the book has been returned to the library?</p>
        <form method="POST" id="returnForm">
            @csrf
            <div style="display:flex; gap:10px; justify-content:flex-end;">
                <button type="button" class="btn btn-outline" onclick="closeReturnModal()">Cancel</button>
                <button type="submit" class="btn btn-success">Return</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openReturnModal(url) {
        document.getElementById('returnForm').action = url;
        document.getElementById('returnModal').style.display = 'flex';
    }
    function closeReturnModal() {
        document.getElementById('returnModal').style.display = 'none';
    }
    document.getElementById('returnModal').addEventListener('click', e => {
        if (e.target === document.getElementById('returnModal')) closeReturnModal();
    });
</script>

@endsection
