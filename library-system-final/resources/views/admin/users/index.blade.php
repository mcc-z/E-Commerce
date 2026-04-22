@extends('layouts.app')
@section('title', 'Manage Members')
@section('page-title', 'Members')

@section('content')
<div class="page-header">
    <div>
        <h1>Members</h1>
        <p>{{ \App\Models\User::members()->count() }} total registered members</p>
    </div>
</div>

<form method="GET" action="{{ route('admin.users.index') }}">
    <div class="search-bar">
        <div class="search-input-wrap" style="flex:2">
            <i class="fas fa-search"></i>
            <input type="text" name="search" placeholder="Search name, email, or member ID..." value="{{ request('search') }}">
        </div>
        <select name="status" class="form-control" style="max-width:150px;padding:10px 14px;">
            <option value="">All Status</option>
            <option value="active"    {{ request('status')=='active'    ? 'selected':'' }}>Active</option>
            <option value="blocked"   {{ request('status')=='blocked'   ? 'selected':'' }}>Blocked</option>
            <option value="suspended" {{ request('status')=='suspended' ? 'selected':'' }}>Suspended</option>
        </select>
        <button type="submit" class="btn btn-primary">Filter</button>
        @if(request()->anyFilled(['search','status']))
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline">Clear</a>
        @endif
    </div>
</form>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Member</th>
                    <th>Member ID</th>
                    <th>Phone</th>
                    <th>Active Borrows</th>
                    <th>Total Borrows</th>
                    <th>Outstanding Fine</th>
                    <th>Status</th>
                    <th>Joined</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:10px;">
                            <img src="{{ $user->avatar_url }}" style="width:34px;height:34px;border-radius:50%;object-fit:cover;">
                            <div>
                                <div style="font-weight:600;font-size:14px;">{{ $user->name }}</div>
                                <div style="font-size:12px;color:var(--text-muted);">{{ $user->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="font-family:monospace;font-size:12px;color:var(--text-muted);">{{ $user->member_id }}</td>
                    <td style="font-size:13px;">{{ $user->phone ?? '—' }}</td>
                    <td style="text-align:center;">
                        <span style="font-weight:700;color:{{ $user->active_borrows_count > 0 ? 'var(--navy)' : 'var(--text-muted)' }}">
                            {{ $user->active_borrows_count }}
                        </span>
                    </td>
                    <td style="text-align:center;font-size:13px;">{{ $user->borrows_count }}</td>
                    <td>
                        @if($user->outstanding_fines > 0)
                            <span style="font-weight:700;color:var(--danger);">${{ number_format($user->outstanding_fines, 2) }}</span>
                        @else
                            <span style="color:var(--success);font-size:13px;"><i class="fas fa-check"></i></span>
                        @endif
                    </td>
                    <td><span class="badge {{ $user->status_badge['class'] }}">{{ $user->status_badge['label'] }}</span></td>
                    <td style="font-size:12px;color:var(--text-muted);">{{ $user->created_at->format('M d, Y') }}</td>
                    <td>
                        <div style="display:flex;gap:4px;">
                            <a href="{{ route('admin.users.show', $user) }}" class="btn btn-outline btn-xs" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-outline btn-xs" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.users.toggle-block', $user) }}" method="POST">
                                @csrf
                                <button class="btn btn-xs {{ $user->status === 'blocked' ? 'btn-success' : 'btn-warning' }}" title="{{ $user->status === 'blocked' ? 'Unblock' : 'Block' }}">
                                    <i class="fas {{ $user->status === 'blocked' ? 'fa-unlock' : 'fa-ban' }}"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9"><div class="empty-state">No members found.</div></td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($users->hasPages())
    <div style="padding:8px 16px 16px;">{{ $users->links('pagination::simple-default') }}</div>
    @endif
</div>
@endsection
