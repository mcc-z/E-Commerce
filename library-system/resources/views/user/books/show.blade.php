@extends('layouts.app')
@section('title', $book->title)
@section('page-title', 'Book Details')

@section('content')
<div style="display:grid;grid-template-columns:280px 1fr;gap:28px;">

    {{-- Book Cover & Quick Actions --}}
    <div>
        <div class="card" style="overflow:hidden;margin-bottom:16px;">
            <div style="height:280px;background:linear-gradient(160deg,var(--navy) 0%,var(--navy-3) 100%);display:flex;align-items:center;justify-content:center;">
                <img src="{{ $book->cover_url }}" alt="{{ $book->title }}"
                     style="width:100%;height:100%;object-fit:cover;">
            </div>
            <div class="card-body">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;">
                    <span class="badge {{ $book->availability_badge['class'] }}" style="font-size:12px;">
                        {{ $book->availability_badge['label'] }}
                    </span>
                    <span style="font-size:13px;color:var(--text-muted);">
                        {{ $book->available_copies }}/{{ $book->total_copies }} copies
                    </span>
                </div>

                @if($isBorrowed)
                    <div class="btn btn-outline" style="width:100%;justify-content:center;cursor:default;">
                        <i class="fas fa-book-reader"></i> Currently Borrowed
                    </div>
                    @if($activeBorrow?->canRenew())
                        <form action="{{ route('user.borrows.renew', $activeBorrow) }}" method="POST" style="margin-top:8px;">
                            @csrf
                            <button class="btn btn-success" style="width:100%;justify-content:center;">
                                <i class="fas fa-redo"></i> Renew Borrow
                            </button>
                        </form>
                    @endif
                @elseif($isReserved)
                    <div style="background:var(--cream);border-radius:8px;padding:12px;font-size:13px;margin-bottom:8px;">
                        <div style="font-weight:600;color:var(--navy);margin-bottom:2px;"><i class="fas fa-bookmark" style="color:var(--gold)"></i> Reservation Active</div>
                        <div style="color:var(--text-muted);">Status: {{ $reservation->status_badge['label'] }}</div>
                        @if($reservation->status === 'ready')
                            <div style="color:var(--success);font-weight:600;margin-top:4px;">✓ Ready for pickup! Pick up by {{ $reservation->expires_at->format('M d') }}</div>
                        @endif
                    </div>
                    <form action="{{ route('user.reservations.cancel', $reservation) }}" method="POST"
                          onsubmit="return confirm('Cancel your reservation?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-outline" style="width:100%;justify-content:center;">
                            <i class="fas fa-times"></i> Cancel Reservation
                        </button>
                    </form>
                @else
                    <form action="{{ route('user.books.reserve', $book) }}" method="POST">
                        @csrf
                        <button class="btn {{ $book->isAvailable() ? 'btn-primary' : 'btn-warning' }}" style="width:100%;justify-content:center;">
                            @if($book->isAvailable())
                                <i class="fas fa-bookmark"></i> Reserve Book
                            @else
                                <i class="fas fa-clock"></i> Join Queue (Position #{{ $queuePosition }})
                            @endif
                        </button>
                    </form>
                @endif
            </div>
        </div>

        {{-- Book Info --}}
        <div class="card">
            <div class="card-body">
                <div style="display:flex;flex-direction:column;gap:10px;">
                    @foreach([
                        ['label'=>'ISBN','value'=>$book->isbn,'icon'=>'fa-barcode'],
                        ['label'=>'Publisher','value'=>$book->publisher,'icon'=>'fa-building'],
                        ['label'=>'Year','value'=>$book->published_year,'icon'=>'fa-calendar'],
                        ['label'=>'Language','value'=>$book->language,'icon'=>'fa-globe'],
                        ['label'=>'Pages','value'=>$book->pages,'icon'=>'fa-file-alt'],
                        ['label'=>'Location','value'=>$book->location,'icon'=>'fa-map-marker-alt'],
                    ] as $info)
                    @if($info['value'])
                    <div style="display:flex;gap:10px;font-size:13px;">
                        <i class="fas {{ $info['icon'] }}" style="width:16px;color:var(--text-muted);margin-top:2px;"></i>
                        <div>
                            <div style="color:var(--text-muted);font-size:11px;">{{ $info['label'] }}</div>
                            <div style="font-weight:500;">{{ $info['value'] }}</div>
                        </div>
                    </div>
                    @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- Main Info --}}
    <div>
        <div class="card" style="margin-bottom:20px;">
            <div class="card-body">
                <div style="font-size:12px;text-transform:uppercase;letter-spacing:0.07em;color:var(--text-muted);margin-bottom:6px;">
                    {{ $book->category->name }}
                </div>
                <h1 style="font-family:'DM Serif Display',serif;font-size:30px;color:var(--navy);margin-bottom:6px;">{{ $book->title }}</h1>
                <div style="font-size:16px;color:var(--text-muted);margin-bottom:16px;">by <strong>{{ $book->author }}</strong></div>

                @if($book->description)
                <p style="line-height:1.8;color:var(--text);font-size:15px;">{{ $book->description }}</p>
                @endif
            </div>
        </div>

        {{-- Similar Books --}}
        @if($similar->count())
        <div class="card">
            <div class="card-header"><span class="card-title">More in {{ $book->category->name }}</span></div>
            <div class="card-body">
                <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(130px,1fr));gap:12px;">
                    @foreach($similar as $s)
                    <a href="{{ route('user.books.show', $s) }}" style="text-decoration:none;color:inherit;">
                        <div style="background:var(--cream);border-radius:8px;padding:12px;text-align:center;transition:background 0.2s;" onmouseover="this.style.background='var(--cream-2)'" onmouseout="this.style.background='var(--cream)'">
                            <img src="{{ $s->cover_url }}" alt="{{ $s->title }}"
                                 style="width:56px;height:78px;object-fit:cover;border-radius:6px;border:1px solid var(--cream-2);margin-bottom:8px;">
                            <div style="font-size:12px;font-weight:600;line-height:1.3;">{{ Str::limit($s->title, 30) }}</div>
                            <div style="font-size:11px;color:var(--text-muted);margin-top:2px;">{{ $s->author }}</div>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
