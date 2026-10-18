{{-- Reusable click-to-load office map (Google Maps embed).

     Rendered only when at least one office has a verified latitude/longitude
     pair, so with unverified locations the page shows nothing at all rather
     than a map centred on a guess.

     Nothing is requested from Google until the visitor presses a button: each
     <iframe> is emitted *without* a src, and resources/js/office-map.js copies
     data-src into src on activation. The frame keeps its height either way, so
     revealing the map cannot shift the page.

     One iframe per verified office. A single Google Maps Embed request shows a
     single location, so four offices are four embeds; putting four markers on
     one map would need the Maps JavaScript API (an API key and a billing
     account). See docs/maps-audit-and-plan.md §4.

     $offices is the same array the office cards are built from, so there is no
     second, hardcoded copy of the office list and no invented coordinates. --}}
@php
    $officeMap = \App\Support\OfficeMap::markers($offices ?? []);
    $officeMapEnabled = (bool) config('site.maps.enabled', true) && $officeMap !== [];
    $officeMapId = 'office-map-'.\Illuminate\Support\Str::lower(\Illuminate\Support\Str::random(10));
@endphp

@if ($officeMapEnabled)
    <section class="office-map" id="{{ $officeMapId }}" data-office-map
             aria-label="HANSA office locations on Google Maps">
        <div class="office-map__grid">
            @foreach ($officeMap as $marker)
                @php $itemId = $officeMapId.'-'.($loop->index + 1); @endphp
                <article class="office-map__item" data-office-map-item>
                    <p class="office-map__name">{{ $marker['name'] }}</p>

                    <div class="office-map__frame">
                        <div class="office-map__gate" data-office-map-gate>
                            <button type="button" class="office-map__load" data-office-map-load
                                    aria-describedby="{{ $itemId }}-note">
                                Load Google Map
                            </button>
                            <p class="office-map__note" id="{{ $itemId }}-note">
                                Loading the map connects to Google and requests content from
                                Google Maps. Nothing is requested until you choose to load it.
                            </p>
                        </div>

                        {{-- No src attribute: the browser makes no request to Google
                             until the button is pressed and the JavaScript copies
                             data-src into src. --}}
                        <iframe class="office-map__embed" data-office-map-embed
                                data-src="{{ $marker['embed'] }}" hidden
                                title="Google map of {{ $marker['name'] }}"
                                allowfullscreen></iframe>
                    </div>

                    <p class="office-map__status" data-office-map-status role="status" aria-live="polite"></p>

                    {{-- Always visible, with or without JavaScript: this is the
                         documented fallback when the embed cannot be shown. --}}
                    <p class="office-map__links">
                        <a href="{{ $marker['directions'] }}" target="_blank" rel="noopener noreferrer"
                           aria-label="Get directions to {{ $marker['name'] }}">Get directions</a>
                    </p>
                </article>
            @endforeach
        </div>
    </section>
@endif
