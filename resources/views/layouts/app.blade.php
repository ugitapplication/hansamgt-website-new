@php
    $breadcrumbs = $breadcrumbs ?? \App\Support\Breadcrumbs::forRoute(request()->route()?->getName());
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('partials.seo', ['seo' => $seo ?? [], 'breadcrumbs' => $breadcrumbs])
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="home header_style_2 sticky_menu flex min-h-screen flex-col bg-white font-body text-slate-800 antialiased {{ ($hasHero ?? false) ? 'has-hero header_transparent' : '' }}">
    <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:z-50 focus:bg-black focus:px-4 focus:py-2 focus:text-white">
        Skip to main content
    </a>

    @include('partials.header')

    <main id="main-content" class="flex-1">
        @include('partials.breadcrumbs', ['breadcrumbs' => $breadcrumbs])
        @yield('content')
    </main>

    @include('partials.footer')

    @include('partials.consent')

    @stack('scripts')
</body>
</html>
