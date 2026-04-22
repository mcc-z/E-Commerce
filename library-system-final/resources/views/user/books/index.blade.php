@extends('layouts.app')
@section('title', 'Browse Books')
@section('page-title', 'Browse Books')

@section('content')

<form method="GET" action="{{ route('user.books.index') }}">
    <div class="search-bar">
        <div class="search-input-wrap" style="flex:2;">
            <i class="fas fa-search"></i>
            <input type="text" name="search" placeholder="Search by title, author, or ISBN..."
                   value="{{ request('search') }}">
        </div>
        <select name="category" class="form-control" style="max-width:180px;padding:10px 14px;">
            <option value="">All Categories</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->slug }}" {{ request('category') == $cat->slug ? 'selected' : '' }}>
                    {{ $cat->name }} ({{ $cat->books_count }})
                </option>
            @endforeach
        </select>
        <select name="availability" class="form-control" style="max-width:160px;padding:10px 14px;">
            <option value="">All Books</option>
            <option value="available" {{ request('availability') == 'available' ? 'selected' : '' }}>Available Only</option>
        </select>
        <select name="sort" class="form-control" style="max-width:150px;padding:10px 14px;">
            <option value="">Sort By</option>
            <option value="title" {{ request('sort') == 'title' ? 'selected' : '' }}>Title A-Z</option>
            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest First</option>
            <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Most Popular</option>
        </select>
        <button type="submit" class="btn btn-primary">Search</button>
        @if(request()->anyFilled(['search','category','availability','sort']))
            <a href="{{ route('user.books.index') }}" class="btn btn-outline">Clear</a>
        @endif
    </div>
</form>

{{-- Results count --}}
<div style="font-size:13px;color:var(--text-muted);margin-bottom:16px;">
    Showing {{ $books->firstItem() }}–{{ $books->lastItem() }} of {{ $books->total() }} books
</div>

{{-- Book Grid --}}
<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:20px;margin-bottom:24px;">
    @forelse($books as $book)
    <div class="card" style="overflow:hidden;transition:transform 0.2s,box-shadow 0.2s;cursor:pointer;"
         onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 12px 36px rgba(0,0,0,0.14)'"
         onmouseout="this.style.transform='';this.style.boxShadow=''">

        {{-- Cover --}}
        <a href="{{ route('user.books.show', $book) }}" style="display:block;text-decoration:none;">
            <div style="height:140px;background:linear-gradient(135deg,var(--navy) 0%,var(--navy-2) 100%);display:flex;align-items:center;justify-content:center;position:relative;">
                <img src="{{ $book->cover_url }}" alt="{{ $book->title }}"
                     style="width:100%;height:100%;object-fit:cover;">
                <div style="position:absolute;top:8px;right:8px;">
                    <span class="badge {{ $book->availability_badge['class'] }}" style="font-size:10px;">
                        {{ $book->availability_badge['label'] }}
                    </span>
                </div>
                @if($book->isReservedByUser($userId))
                    <div style="position:absolute;top:8px;left:8px;">
                        <span class="badge badge-purple" style="font-size:10px;"><i class="fas fa-bookmark"></i></span>
                    </div>
                @endif
            </div>
        </a>

        <div style="padding:14px;">
            <div style="font-size:10px;text-transform:uppercase;letter-spacing:0.06em;color:var(--text-muted);margin-bottom:4px;">
                {{ $book->category->name }}
            </div>
            <a href="{{ route('user.books.show', $book) }}" style="text-decoration:none;color:var(--navy);">
                <div style="font-size:14px;font-weight:700;line-height:1.3;margin-bottom:2px;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
                    {{ $book->title }}
                </div>
            </a>
            <div style="font-size:12px;color:var(--text-muted);margin-bottom:10px;">{{ $book->author }}</div>

            <div style="display:flex;gap:6px;flex-wrap:wrap;">
                @if($book->isAvailable() && !$book->isBorrowedByUser($userId))
                    <form action="{{ route('user.books.reserve', $book) }}" method="POST" style="flex:1;">
                        @csrf
                        <button class="btn btn-primary btn-sm" style="width:100%;justify-content:center;">
                            <i class="fas fa-bookmark"></i> Reserve
                        </button>
                    </form>
                @elseif($book->isReservedByUser($userId))
                    <span class="btn btn-outline btn-sm" style="flex:1;text-align:center;cursor:default;">
                        <i class="fas fa-check"></i> Reserved
                    </span>
                @elseif($book->isBorrowedByUser($userId))
                    <span class="btn btn-outline btn-sm" style="flex:1;text-align:center;cursor:default;">
                        <i class="fas fa-book-reader"></i> Borrowed
                    </span>
                @else
                    <form action="{{ route('user.books.reserve', $book) }}" method="POST" style="flex:1;">
                        @csrf
                        <button class="btn btn-warning btn-sm" style="width:100%;justify-content:center;">
                            <i class="fas fa-clock"></i> Queue
                        </button>
                    </form>
                @endif
                <a href="{{ route('user.books.show', $book) }}" class="btn btn-outline btn-sm">
                    <i class="fas fa-info"></i>
                </a>
            </div>
        </div>
    </div>
    @empty
    <div class="empty-state" style="grid-column:1/-1;padding:80px 20px;">
        <i class="fas fa-search" style="font-size:48px;"></i>
        <h3>No books found</h3>
        <p>Try adjusting your search terms or filters.</p>
        <a href="{{ route('user.books.index') }}" class="btn btn-primary" style="margin-top:16px;">Browse All Books</a>
    </div>
    @endforelse
</div>

{{ $books->links('pagination::simple-default') }}

@endsection
