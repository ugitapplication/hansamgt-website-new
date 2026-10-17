<header id="header">
    <div class="header_top clearfix">
        <div class="container">
            <div class="logo media-left media-middle">
                <a href="{{ url('/') }}"><img src="{{ \App\Support\Settings::imageUrl('logo') }}" style="height: 80px;margin-top: 13px;" alt="{{ \App\Support\Settings::get('legal_name') }} logo" /></a>
            </div>
            <div class="top_nav media-body media-middle">
                <div class="top_nav_wrapper clearfix">
                    @include('partials.nav')
                </div>

                <div class="header_socials">
                    @if (\App\Support\Settings::filled('parent_url'))
                        <a target="_blank" rel="noopener" href="{{ \App\Support\Settings::get('parent_url') }}"><img src="{{ asset('images/v4.gif') }}" style="height:55px;margin-top: -10px;" alt="{{ \App\Support\Settings::get('parent_name') }}" /></a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="mobile_header">
        <div class="logo_wrapper clearfix">
            <div class="logo">
                <a href="{{ url('/') }}"><img src="{{ \App\Support\Settings::imageUrl('logo') }}" style="height: 70px;" alt="{{ \App\Support\Settings::get('legal_name') }} logo" /></a>
            </div>
            <div id="menu_toggle">
                <button type="button" aria-label="Toggle navigation menu" aria-expanded="false" aria-controls="mobile-menu-panel"></button>
            </div>
        </div>
        <div class="header_info" id="mobile-menu-panel">
            <div class="top_nav_mobile">
                @include('partials.nav-mobile')
            </div>
        </div>
    </div>
</header>
