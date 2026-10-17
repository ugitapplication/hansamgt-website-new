@extends('layouts.app')

@section('content')
    <div class="page-wrap">
        <div class="insight-layout">
            <div class="insight-layout__main">
                <article class="insight">
                    <h1 class="page-h1 page-h1--sm">{{ $post->title }}</h1>

                    @if ($post->published_date)
                        <p class="insight__meta">
                            <time datetime="{{ $post->published_date->toDateString() }}">{{ $post->published_date->format('d F Y') }}</time>
                        </p>
                    @endif

                    @if ($post->coverImageUrl() !== '')
                        <img class="insight__cover"
                             src="{{ $post->coverImageUrl() }}"
                             alt="{{ $post->cover_image_alt ?: $post->title }}"
                             width="1120" height="630" decoding="async">
                    @endif

                    @if ($post->excerpt)
                        <p class="insight__lede">{{ $post->excerpt }}</p>
                    @endif

                    <div class="page-body insight__body">
                        {!! $post->body !!}
                    </div>
                </article>

                <p class="insight__footer">
                    <a class="insight__back" href="{{ url('insights') }}">&lsaquo; All insights</a>
                </p>
            </div>

            {{-- Hidden below two other articles: a one-item sidebar reads as
                 broken rather than useful. --}}
            @if ($related->count() >= 2)
                <aside class="insight-aside">
                    <nav class="insight-aside__inner" aria-labelledby="insight-aside-title">
                        <h2 class="insight-aside__title" id="insight-aside-title">More insights</h2>

                        <ul class="insight-aside__list">
                            @foreach ($related as $item)
                                <li class="insight-aside__item">
                                    <a class="insight-aside__link" href="{{ url($item->path()) }}">
                                        @if ($item->coverImageUrl() !== '')
                                            <img class="insight-aside__thumb"
                                                 src="{{ $item->coverImageUrl() }}"
                                                 alt=""
                                                 width="88" height="66" loading="lazy" decoding="async">
                                        @endif
                                        <span class="insight-aside__text">
                                            @if ($item->published_date)
                                                <time class="insight-aside__date" datetime="{{ $item->published_date->toDateString() }}">{{ $item->published_date->format('d M Y') }}</time>
                                            @endif
                                            <span class="insight-aside__name">{{ $item->title }}</span>
                                        </span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>

                        <a class="insight-aside__all" href="{{ url('insights') }}">View all insights &rsaquo;</a>
                    </nav>
                </aside>
            @endif
        </div>
    </div>
    @include('pages.home.sections._slogan')
@endsection
