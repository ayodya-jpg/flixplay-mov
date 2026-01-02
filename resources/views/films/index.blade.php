@extends('layouts.app')
@section('title', 'Semua Film')
@section('content')

<section class="category-section" style="margin-top: 90px;">
    <h2 class="category-title">📽️ SEMUA FILM</h2>

    {{-- Flash Message --}}
    @if(session('success'))
        <div style="background: rgba(76,175,80,.2); color:#4CAF50; padding:15px 20px; border-radius:8px; margin-bottom:20px;">
            <i class="bi bi-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    @if(session('info'))
        <div style="background: rgba(33,150,243,.2); color:#2196F3; padding:15px 20px; border-radius:8px; margin-bottom:20px;">
            <i class="bi bi-info-circle"></i> {{ session('info') }}
        </div>
    @endif

    {{-- Film Grid (TAMPILAN CODE 2) --}}
    <div class="movie-container">
        @forelse($films as $film)
            <div class="movie-card">
                <img src="{{ asset($film->poster_url) }}" alt="{{ $film->title }}">
                <div class="movie-overlay">
                    <div class="movie-title">{{ $film->title }}</div>
                    <div class="movie-rating">⭐ {{ number_format($film->rating,1) }}/10</div>
                    <div style="font-size:11px;color:#b0b0b0;margin-bottom:10px;">
                        {{ $film->genre->name }} • {{ $film->release_year }}
                    </div>

                    <div class="movie-actions">
                        <a href="{{ route('films.show',$film) }}" class="icon-btn">
                            <i class="bi bi-play-fill"></i>
                        </a>

                        @auth
                            @if(auth()->user()->hasInWatchlist($film->id))
                                <form action="{{ route('watchlist.destroy',$film) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button class="icon-btn" style="background:#4CAF50">
                                        <i class="bi bi-check"></i>
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('watchlist.store',$film) }}" method="POST">
                                    @csrf
                                    <button class="icon-btn">
                                        <i class="bi bi-plus"></i>
                                    </button>
                                </form>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="icon-btn">
                                <i class="bi bi-plus"></i>
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        @empty
            <p style="color:#b0b0b0">Belum ada film</p>
        @endforelse
    </div>

    @if($films->hasPages())
        <div style="display:flex; justify-content:center; gap:10px; margin-top:40px; flex-wrap:wrap;">

            {{-- Previous --}}
            @if ($films->onFirstPage())
                <span style="padding:10px 16px; background:#333; color:#777; border-radius:8px;">
                    « Previous
                </span>
            @else
                <a href="{{ $films->previousPageUrl() }}"
                   style="padding:10px 16px; background:rgba(255,255,255,.1); color:#fff; border-radius:8px; text-decoration:none;">
                    « Previous
                </a>
            @endif

            {{-- Page Numbers --}}
            @foreach ($films->getUrlRange(1, $films->lastPage()) as $page => $url)
                @if ($page == $films->currentPage())
                    <span style="padding:10px 14px; background:#e94b3c; color:white; border-radius:8px; font-weight:bold;">
                        {{ $page }}
                    </span>
                @else
                    <a href="{{ $url }}"
                       style="padding:10px 14px; background:rgba(255,255,255,.1); color:#fff; border-radius:8px; text-decoration:none;">
                        {{ $page }}
                    </a>
                @endif
            @endforeach

            {{-- Next --}}
            @if ($films->hasMorePages())
                <a href="{{ $films->nextPageUrl() }}"
                   style="padding:10px 16px; background:rgba(255,255,255,.1); color:#fff; border-radius:8px; text-decoration:none;">
                    Next »
                </a>
            @else
                <span style="padding:10px 16px; background:#333; color:#777; border-radius:8px;">
                    Next »
                </span>
            @endif
        </div>
    @endif
</section>

@endsection
