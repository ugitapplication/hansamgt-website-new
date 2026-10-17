@php($crumbs = $breadcrumbs ?? [])
@if (count($crumbs) > 1)
    <nav class="breadcrumbs" aria-label="Breadcrumb">
        <div class="breadcrumbs__inner">
            <ol class="breadcrumbs__list">
                @foreach ($crumbs as $crumb)
                    <li class="breadcrumbs__item">
                        @if (! empty($crumb['url']) && ! $loop->last)
                            <a href="{{ $crumb['url'] }}">{{ $crumb['label'] }}</a>
                        @else
                            <span aria-current="page">{{ $crumb['label'] }}</span>
                        @endif
                    </li>
                @endforeach
            </ol>
        </div>
    </nav>
@endif
