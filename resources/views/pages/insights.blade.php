@extends('layouts.app')

@section('content')
    <div class="page-wrap">
        <h1 class="page-h1">Insights</h1>
        <div class="page-body">
            <p>Guides, updates and practical notes on hospitality and facility management from HANSA Management Ltd.</p>
        </div>

        @if ($posts->isEmpty())
            <div class="insights-empty">
                <p>New articles are on the way.</p>
                <p>
                    In the meantime, explore our
                    <a href="{{ url('hospitality-management') }}">services</a> or
                    <a href="{{ url('contact') }}">get in touch</a>.
                </p>
            </div>
        @else
            <div class="insights-grid">
                @foreach ($posts as $post)
                    <article class="insight-card">
                        @if ($post->coverImageUrl() !== '')
                            <a class="insight-card__media" href="{{ route('insights.show', ['slug' => $post->slug]) }}" tabindex="-1" aria-hidden="true">
                                <img src="{{ $post->coverImageUrl() }}"
                                     alt="{{ $post->cover_image_alt ?: $post->title }}"
                                     width="600" height="400" loading="lazy" decoding="async">
                            </a>
                        @endif
                        <div class="insight-card__body">
                            @if ($post->published_date)
                                <time class="insight-card__date" datetime="{{ $post->published_date->toDateString() }}">{{ $post->published_date->format('d M Y') }}</time>
                            @endif
                            <h2 class="insight-card__title">
                                <a href="{{ route('insights.show', ['slug' => $post->slug]) }}">{{ $post->title }}</a>
                            </h2>
                            <p class="insight-card__excerpt">{{ $post->summary(150) }}</p>
                            <a class="insight-card__more" href="{{ route('insights.show', ['slug' => $post->slug]) }}">Read article <span aria-hidden="true">&rsaquo;</span></a>
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="insights-pagination">
                {{ $posts->links() }}
            </div>
        @endif
    </div>
    @include('pages.home.sections._slogan')
@endsection
