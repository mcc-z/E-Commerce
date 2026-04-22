@extends('layouts.app')
@section('title', 'Manage Books')
@section('page-title', 'Books')

@section('content')
<div class="page-header">
    <div>
        <h1>Books</h1>
        <p>{{ \App\Models\Book::count() }} total books in catalog</p>
    </div>
    <a href="{{ route('admin.books.create') }}" class="btn btn-gold">
        <i class="fas fa-plus"></i> Add New Book
    </a>
</div>

<form method="GET" action="{{ route('admin.books.index') }}">
    <div class="search-bar">
        <div class="search-input-wrap" style="flex:2">
            <i class="fas fa-search"></i>
            <input type="text" name="search" placeholder="Search title, author, ISBN..." value="{{ request('search') }}">
        </div>
        <select name="category" class="form-control" style="max-width:180px;padding:10px 14px;">
            <option value="">All Categories</option>
            @foreach($categories as $cat)
            <option value="{{ $cat->id }}" {{ request('category')==$cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
            @endforeach
        </select>
        <select name="status" class="form-control" style="max-width:150px;padding:10px 14px;">
            <option value="">All Status</option>
            <option value="available" {{ request('status')=='available' ? 'selected':'' }}>Available</option>
            <option value="unavailable" {{ request('status')=='unavailable' ? 'selected':'' }}>Unavailable</option>
        </select>
        <button type="submit" class="btn btn-primary">Filter</button>
        @if(request()->anyFilled(['search','category','status']))
        <a href="{{ route('admin.books.index') }}" class="btn btn-outline">Clear</a>
        @endif
    </div>
</form>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Book</th>
                    <th>ISBN</th>
                    <th>Category</th>
                    <th>Location</th>
                    <th>Copies</th>
                    <th>Available</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($books as $book)
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:12px;">
                            <img src="{{ $book->cover_url }}" alt="{{ $book->title }}"
                                 style="width:42px;height:60px;object-fit:cover;border-radius:8px;border:1px solid var(--cream-2);flex-shrink:0;">
                            <div>
                                <div style="font-weight:600;font-size:14px;">{{ $book->title }}</div>
                                <div style="font-size:12px;color:var(--text-muted);">{{ $book->author }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="font-family:monospace;font-size:12px;color:var(--text-muted);">{{ $book->isbn }}</td>
                    <td>
                        <span style="display:inline-flex;align-items:center;gap:5px;font-size:12px;">
                            <span style="width:8px;height:8px;border-radius:50%;background:{{ $book->category->color }}"></span>
                            {{ $book->category->name }}
                        </span>
                    </td>
                    <td style="font-size:13px;font-family:monospace;">{{ $book->location ?? '—' }}</td>
                    <td style="text-align:center;font-weight:600;">{{ $book->total_copies }}</td>
                    <td style="text-align:center;">
                        <span style="font-weight:700;color:{{ $book->available_copies > 0 ? 'var(--success)' : 'var(--danger)' }}">
                            {{ $book->available_copies }}
                        </span>
                    </td>
                    <td><span class="badge {{ $book->availability_badge['class'] }}">{{ $book->availability_badge['label'] }}</span></td>
                    <td>
                        <div style="display:flex;gap:4px;">
                            <a href="{{ route('admin.books.edit', $book) }}" class="btn btn-outline btn-xs"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('admin.books.destroy', $book) }}" method="POST"
                                  onsubmit="return confirm('Delete this book?')">
                                @csrf @method('DELETE')
                                <button type="button" class="btn btn-danger btn-xs" onclick="openModal('{{ route('admin.books.destroy', $book) }}')"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8"><div class="empty-state">No books found.</div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($books->hasPages())
    <div style="padding:8px 16px 16px;">{{ $books->links('pagination::simple-default') }}</div>
    @endif
</div>

<div class="modal-overlay" id="deleteModal">
    <div class="modal-content">
        <h3>Confirm Deletion</h3>
        <p>Are you sure you want to delete this book?</p>
        <form method="POST" id="deleteForm">
            @csrf @method('DELETE')
            <div style="display:flex; gap:10px; justify-content:flex-end; margin-top:20px;">
                <button type="button" onclick="closeModal()" class="btn btn-outline">Cancel</button>
                <button type="submit" class="btn btn-danger">Delete</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openModal(url) {
        document.getElementById('deleteForm').action = url;
        document.getElementById('deleteModal').style.display = 'flex';
    }
    function closeModal() {
        document.getElementById('deleteModal').style.display = 'none';
    }
</script>
@endsection
