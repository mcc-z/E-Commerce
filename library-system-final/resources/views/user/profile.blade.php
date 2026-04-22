@extends('layouts.app')
@section('title', 'My Profile')
@section('page-title', 'My Profile')

@section('content')
@php($profileRoutePrefix = auth()->user()->isAdmin() ? 'admin' : 'user')
<div style="display:grid;grid-template-columns:300px 1fr;gap:24px;">

    {{-- Profile Card --}}
    <div>
        <div class="card" style="text-align:center;padding:28px 20px;margin-bottom:16px;">
            <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}"
                 style="width:90px;height:90px;border-radius:50%;object-fit:cover;border:3px solid var(--gold);margin:0 auto 14px;">
            <h2 style="font-family:'DM Serif Display',serif;font-size:20px;color:var(--navy);margin-bottom:4px;">{{ $user->name }}</h2>
            <div style="color:var(--text-muted);font-size:13px;margin-bottom:10px;">{{ $user->email }}</div>
            <span class="badge badge-info" style="font-size:12px;">
                {{ $user->isAdmin() ? 'Administrator' : $user->member_id }}
            </span>

            <div style="margin-top:16px;padding-top:16px;border-top:1px solid var(--cream-2);display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                <div style="text-align:center;">
                    <div style="font-size:22px;font-weight:700;color:var(--navy);">
                        {{ $user->isAdmin() ? ($adminStats['members'] ?? 0) : $user->total_borrowed }}
                    </div>
                    <div style="font-size:11px;color:var(--text-muted);text-transform:uppercase;">
                        {{ $user->isAdmin() ? 'Members' : 'Borrowed' }}
                    </div>
                </div>
                <div style="text-align:center;">
                    <div style="font-size:22px;font-weight:700;color:{{ $user->isAdmin() ? 'var(--info)' : ($user->outstanding_fines > 0 ? 'var(--danger)' : 'var(--success)') }};">
                        {{ $user->isAdmin() ? ($adminStats['active_borrows'] ?? 0) : '$' . number_format($user->outstanding_fines, 2) }}
                    </div>
                    <div style="font-size:11px;color:var(--text-muted);text-transform:uppercase;">
                        {{ $user->isAdmin() ? 'Active Borrows' : 'Fines' }}
                    </div>
                </div>
            </div>

            <a href="{{ route($profileRoutePrefix . '.profile.edit') }}" class="btn btn-primary" style="margin-top:16px;width:100%;justify-content:center;">
                <i class="fas fa-edit"></i> Edit Profile
            </a>
        </div>

        <div class="card">
            <div class="card-body">
                <div style="font-size:13px;font-weight:600;color:var(--navy);margin-bottom:12px;">Account Info</div>
                @foreach([
                    ['icon'=>'fa-phone','label'=>'Phone','value'=>$user->phone ?? 'Not set'],
                    ['icon'=>'fa-map-marker-alt','label'=>'Address','value'=>$user->address ?? 'Not set'],
                    ['icon'=>'fa-calendar','label'=>$user->isAdmin() ? 'Admin Since' : 'Member Since','value'=>$user->created_at->format('M Y')],
                    ['icon'=>'fa-shield-alt','label'=>'Status','value'=>$user->status_badge['label']],
                ] as $info)
                <div style="display:flex;gap:10px;margin-bottom:12px;font-size:13px;">
                    <i class="fas {{ $info['icon'] }}" style="width:16px;color:var(--text-muted);margin-top:2px;"></i>
                    <div>
                        <div style="color:var(--text-muted);font-size:11px;">{{ $info['label'] }}</div>
                        <div style="font-weight:500;">{{ $info['value'] }}</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        @if($user->isAdmin())
        <div class="card">
            <div class="card-body">
                <div style="font-size:13px;font-weight:600;color:var(--navy);margin-bottom:12px;">Quick Access</div>
                <div style="display:grid;gap:10px;">
                    <a href="{{ route('admin.books.index') }}" class="btn btn-outline" style="justify-content:flex-start;">
                        <i class="fas fa-books"></i> Manage Books
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline" style="justify-content:flex-start;">
                        <i class="fas fa-users"></i> Manage Members
                    </a>
                    <a href="{{ route('admin.borrows.index') }}" class="btn btn-outline" style="justify-content:flex-start;">
                        <i class="fas fa-hand-holding-heart"></i> Review Borrows
                    </a>
                    <a href="{{ route('admin.payments.index') }}" class="btn btn-outline" style="justify-content:flex-start;">
                        <i class="fas fa-money-bill-wave"></i> Payments
                    </a>
                </div>
            </div>
        </div>
        @endif
    </div>

    <div>
        @if($user->isAdmin())
        <div class="stat-grid">
            <div class="stat-card">
                <div class="stat-icon blue"><i class="fas fa-books"></i></div>
                <div>
                    <div class="stat-value">{{ $adminStats['total_books'] }}</div>
                    <div class="stat-label">Books In Catalog</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon gold"><i class="fas fa-users"></i></div>
                <div>
                    <div class="stat-value">{{ $adminStats['members'] }}</div>
                    <div class="stat-label">Registered Members</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon purple"><i class="fas fa-book-reader"></i></div>
                <div>
                    <div class="stat-value">{{ $adminStats['active_borrows'] }}</div>
                    <div class="stat-label">Active Borrows</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon teal"><i class="fas fa-clock"></i></div>
                <div>
                    <div class="stat-value">{{ $adminStats['reservations'] }}</div>
                    <div class="stat-label">Open Reservations</div>
                </div>
            </div>
        </div>

        <div style="display:grid;grid-template-columns:1.2fr 0.8fr;gap:24px;">
            <div class="card">
                <div class="card-header">
                    <span class="card-title">Recent Activity</span>
                </div>
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Action</th>
                                <th>User</th>
                                <th>When</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentActivity as $log)
                            <tr>
                                <td>
                                    <div style="font-weight:600;font-size:14px;">{{ ucwords(str_replace('_', ' ', $log->action)) }}</div>
                                    <div style="font-size:12px;color:var(--text-muted);">{{ $log->description ?: 'No description' }}</div>
                                </td>
                                <td style="font-size:13px;">{{ $log->user?->name ?? 'System' }}</td>
                                <td style="font-size:13px;">{{ $log->created_at->diffForHumans() }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="3"><div class="empty-state" style="padding:30px;">No recent activity yet.</div></td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div style="display:grid;gap:24px;">
                <div class="card">
                    <div class="card-header">
                        <span class="card-title">Newest Members</span>
                    </div>
                    <div class="card-body">
                        @forelse($recentMembers as $member)
                            <div style="display:flex;align-items:center;justify-content:space-between;padding:10px 0;border-bottom:1px solid var(--cream-2);gap:12px;">
                                <div>
                                    <div style="font-weight:600;color:var(--navy);">{{ $member->name }}</div>
                                    <div style="font-size:12px;color:var(--text-muted);">{{ $member->email }}</div>
                                </div>
                                <span class="badge {{ $member->status_badge['class'] }}">{{ $member->status_badge['label'] }}</span>
                            </div>
                        @empty
                            <div class="empty-state" style="padding:20px;">No members found.</div>
                        @endforelse
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <span class="card-title">Latest Books</span>
                    </div>
                    <div class="card-body">
                        @forelse($recentBooks as $book)
                            <div style="display:flex;align-items:center;justify-content:space-between;padding:10px 0;border-bottom:1px solid var(--cream-2);gap:12px;">
                                <div>
                                    <div style="font-weight:600;color:var(--navy);">{{ $book->title }}</div>
                                    <div style="font-size:12px;color:var(--text-muted);">{{ $book->category?->name ?? 'Uncategorized' }}</div>
                                </div>
                                <span class="badge {{ $book->availability_badge['class'] }}">{{ $book->availability_badge['label'] }}</span>
                            </div>
                        @empty
                            <div class="empty-state" style="padding:20px;">No books found.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
        @else
        <div class="card">
            <div class="card-header">
                <span class="card-title">Borrow History</span>
                <a href="{{ route('user.borrows.index') }}" class="btn btn-outline btn-sm">View All</a>
            </div>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Book</th>
                            <th>Borrowed</th>
                            <th>Due Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($borrowHistory as $borrow)
                        <tr>
                            <td>
                                <div style="font-weight:600;font-size:14px;">{{ $borrow->book->title }}</div>
                                <div style="font-size:12px;color:var(--text-muted);">{{ $borrow->book->author }}</div>
                            </td>
                            <td style="font-size:13px;">{{ $borrow->borrowed_at->format('M d, Y') }}</td>
                            <td style="font-size:13px;">{{ $borrow->due_date->format('M d, Y') }}</td>
                            <td><span class="badge {{ $borrow->status_badge['class'] }}">{{ $borrow->status_badge['label'] }}</span></td>
                        </tr>
                        @empty
                        <tr><td colspan="4"><div class="empty-state" style="padding:30px;">No borrow history yet.</div></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($borrowHistory->hasPages())
            <div style="padding:8px 16px 16px;">{{ $borrowHistory->links() }}</div>
            @endif
        </div>
        @endif
    </div>
</div>
@endsection
