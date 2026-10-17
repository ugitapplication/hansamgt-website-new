{{-- Social profiles, from Settings → Site Settings.
     Profiles with no URL are omitted by App\Support\Settings::socialLinks(), so
     a placeholder '#' link can never be published by accident.
     $style: 'footer' — round buttons on the dark footer;
             'card'   — plain icons inside a contact card (sized by CSS). --}}
@php
    $links = \App\Support\Settings::socialLinks();
    $style = $style ?? 'footer';
@endphp
@if (count($links) > 0)
    @if ($style === 'footer')
        <ul class="mt-4 flex flex-wrap gap-3">
            @foreach ($links as $link)
                <li>
                    <a href="{{ $link['url'] }}" target="_blank" rel="noopener"
                       aria-label="HANSA on {{ $link['label'] }}" title="{{ $link['label'] }}"
                       class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-white text-black transition-colors duration-300 hover:text-white {{
                           match ($link['key']) {
                               'facebook' => 'hover:bg-[#1877F2]',
                               'linkedin' => 'hover:bg-[#0A66C2]',
                               'youtube' => 'hover:bg-[#FF0000]',
                               'twitter' => 'hover:bg-[#111111]',
                               'instagram' => 'hover:bg-[#E1306C]',
                               default => 'hover:bg-slate-600',
                           }
                       }}">
                        @include('partials.social-icon', ['key' => $link['key'], 'class' => 'h-4 w-4'])
                    </a>
                </li>
            @endforeach
        </ul>
    @else
        <ul class="contact-card__socials">
            @foreach ($links as $link)
                <li>
                    <a href="{{ $link['url'] }}" target="_blank" rel="noopener"
                       aria-label="HANSA on {{ $link['label'] }}" title="{{ $link['label'] }}">
                        @include('partials.social-icon', ['key' => $link['key'], 'class' => ''])
                    </a>
                </li>
            @endforeach
        </ul>
    @endif
@endif
