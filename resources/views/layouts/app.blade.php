<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('partials.seo', ['seo' => $seo ?? []])
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="home header_style_2 sticky_menu flex min-h-screen flex-col bg-white font-body text-slate-800 antialiased {{ ($hasHero ?? false) ? 'has-hero header_transparent' : '' }}">
    <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:z-50 focus:bg-black focus:px-4 focus:py-2 focus:text-white">
        Skip to main content
    </a>

    @include('partials.header')
    <script>
    (function () {
        var header = document.getElementById('header');
        var sticky = window.scrollY > 80;
        var wasInFlow = header && getComputedStyle(header).position !== 'fixed' && getComputedStyle(header).position !== 'absolute';
        document.body.classList.toggle('header_sticky', sticky);
        if (sticky && wasInFlow && header && getComputedStyle(header).position === 'fixed') {
            document.body.style.paddingTop = header.getBoundingClientRect().height + 'px';
        }
    })();
    </script>

    <main id="main-content" class="flex-1">
        @yield('content')
    </main>

    @include('partials.footer')

    @stack('scripts')
</body>
</html>
