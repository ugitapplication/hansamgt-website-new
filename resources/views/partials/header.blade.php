<header id="header">
    <div class="header_top clearfix">
        <div class="container">
            <div class="logo media-left media-middle">
                <a href="{{ url('/') }}"><img src="{{ asset('images/hansa_logo_new.png') }}" style="height: 80px;margin-top: 13px;" alt="Hansa" /></a>
            </div>
            <div class="top_nav media-body media-middle">
                <div class="top_nav_wrapper clearfix">
                    @include('partials.nav')
                </div>

                <div class="header_socials">
                    <a target="_blank" href="https://uniquegroupbd.com"><img src="{{ asset('images/v4.gif') }}" style="height:55px;margin-top: -10px;" alt="Hansa" /></a>

                </div>
            </div>
        </div>
    </div>

    <div class="mobile_header">
        <div class="logo_wrapper clearfix">
            <div class="logo">
                <a href="{{ url('/') }}"><img src="{{ asset('images/hansa_logo_new.png') }}" style="height: 70px;" alt="Hansa" /></a>
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
