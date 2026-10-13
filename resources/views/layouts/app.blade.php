<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('partials.seo', ['seo' => $seo ?? []])
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="home header_style_2 sticky_menu bg-white font-body text-slate-800 antialiased {{ ($hasHero ?? false) ? 'has-hero header_transparent' : '' }}">
    <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:z-50 focus:bg-hansa-navy focus:px-4 focus:py-2 focus:text-white">
        Skip to main content
    </a>

    @include('partials.header')

    <main id="main-content">
        @yield('content')
    </main>

    @include('partials.footer')

    @stack('scripts')
</body>
</html>
