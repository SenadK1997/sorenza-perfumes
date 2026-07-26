<!DOCTYPE html>
<html lang="bs" prefix="og: https://ogp.me/ns#">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#7c3aed">

    {{-- 1. Brzo povezivanje (bez duplikata) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://www.googletagmanager.com">
    <link rel="preconnect" href="https://www.google-analytics.com">

    {{-- 2. Optimizovano učitavanje fonta --}}
    <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" media="print" onload="this.media='all'">

    <noscript>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap">
    </noscript>

    {{-- 2. FAVICONS (Google Priority - Mora biti pri vrhu) --}}
    {{-- Glavna ikona za tabove i Google Search (48x48 multiple) --}}
    <link rel="icon" type="image/png" sizes="48x48" href="{{ asset('favicon-48x48.png') }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    
    {{-- Ostale rezolucije za uređaje --}}
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="icon" sizes="192x192" href="{{ asset('favicon-512x512.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    {{-- <link rel="manifest" href="{{ asset('site.webmanifest') }}"> --}}

    {{-- 3. TITLE & DESCRIPTION --}}
    <title>@yield('title', 'Sorenza - Luksuzni Parfemi | Online Parfumerija BiH')</title>
    <meta name="description" content="@yield('meta_description', 'Sorenza parfumerija - Kupite originalne luksuzne parfeme online. Širok izbor muških i ženskih parfema. Brza dostava u BiH.')">
    <meta name="keywords" content="@yield('meta_keywords', 'parfemi, parfem, luksuzni parfemi, originalni parfemi, online parfumerija, parfemi BiH, parfemi Sarajevo, muški parfemi, ženski parfemi, unisex parfemi')">
    <meta name="author" content="Sorenza">

    {{-- 4. ROBOTS LOGIKA (Ključno za SEO i sakrivanje korpe) --}}
    @if(request()->is('cart*', 'checkout*', 'admin*'))
        <meta name="robots" content="noindex, nofollow">
    @else
        <meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
    @endif

    {{-- 5. CANONICAL & GEO --}}
    <link rel="canonical" href="{{ url()->current() }}">
    <link rel="alternate" hreflang="bs" href="{{ url()->current() }}">
    <link rel="alternate" hreflang="hr" href="{{ url()->current() }}">
    <link rel="alternate" hreflang="sr" href="{{ url()->current() }}">
    <link rel="alternate" hreflang="x-default" href="{{ url()->current() }}">

    <meta name="geo.region" content="BA">
    <meta name="geo.placename" content="Bosnia and Herzegovina">
    <meta name="geo.position" content="43.8563;18.4131">
    <meta name="ICBM" content="43.8563, 18.4131">

    {{-- 6. OPEN GRAPH (Facebook/WhatsApp) --}}
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:site_name" content="Sorenza Parfemi">
    <meta property="og:title" content="@yield('og_title', 'Sorenza - Luksuzni Parfemi Online | BiH, HR, SRB')">
    <meta property="og:description" content="@yield('og_description', 'Otkrijte kolekciju luksuznih parfema u Sorenza online parfumeriji. Originalni brendovi i brza dostava.')">
    <meta property="og:image" content="@yield('og_image', asset('favicon.png'))">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:locale" content="bs_BA">

    {{-- 7. TWITTER --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ url()->current() }}">
    <meta name="twitter:title" content="@yield('twitter_title', 'Sorenza - Luksuzni Parfemi')">
    <meta name="twitter:description" content="@yield('twitter_description', 'Premium parfemi i mirisi. Kupujte online.')">
    <meta name="twitter:image" content="@yield('twitter_image', asset('favicon.png'))">

    {{-- 8. SCHEMA.ORG (JSON-LD Structured Data) --}}
    {{-- Local Business --}}
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "Store",
        "name": "Sorenza Parfemi",
        "alternateName": ["Sorenza", "Sorenza Parfumerija"],
        "description": "Online prodavnica luksuznih i originalnih parfema. Dostava u BiH.",
        "url": "{{ url('/') }}",
        "logo": "{{ asset('storage/images/sorenza-logo.jpg') }}",
        "image": "{{ asset('images/sorenza-og.jpg') }}",
        "priceRange": "$$",
        "currenciesAccepted": "BAM, EUR",
        "paymentAccepted": "Cash, Credit Card, Bank Transfer",
        "address": {
            "@@type": "PostalAddress",
            "addressCountry": "BA",
            "addressRegion": "Bosna i Hercegovina"
        },
        "areaServed": [{"@@type": "Country", "name": "Bosnia and Herzegovina"}]
    }
    </script>

    {{-- WebSite --}}
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "WebSite",
        "name": "Sorénza",
        "url": "https://sorenzaperfumes.com/",
        "potentialAction": {
            "@@type": "SearchAction",
            "target": "{{ url('/shop') }}?search={search_term_string}",
            "query-input": "required name=search_term_string"
        }
    }
    </script>

    {{-- Organization --}}
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "Organization",
        "name": "Sorenza",
        "url": "{{ url('/') }}",
        "logo": "{{ asset('storage/images/sorenza-logo.jpg') }}",
        "sameAs": []
    }
    </script>

    {{-- BreadcrumbList --}}
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "BreadcrumbList",
        "itemListElement": [
            { "@@type": "ListItem", "position": 1, "name": "Početna", "item": "{{ url('/') }}" },
            { "@@type": "ListItem", "position": 2, "name": "Parfemi", "item": "{{ url('/shop') }}" }
        ]
    }
    </script>

    {{-- FAQ --}}
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "FAQPage",
        "mainEntity": [
            {
                "@@type": "Question",
                "name": "Gdje mogu kupiti originalne parfeme online u BiH?",
                "acceptedAnswer": { "@@type": "Answer", "text": "Originalne parfeme možete kupiti online u Sorenza parfumeriji uz brzu dostavu." }
            },
            {
                "@@type": "Question",
                "name": "Koje brendove parfema nudite?",
                "acceptedAnswer": { "@@type": "Answer", "text": "Nudimo brendove kao što su Chanel, Dior, Versace, Armani i mnoge druge." }
            }
        ]
    }
    </script>

    @yield('structured_data')
    {{-- 9. STYLES --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <script>
        window.addEventListener('load', function() {
            (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
            new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
            j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
            'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
            })(window,document,'script','dataLayer','GTM-NDZ9D5BK');
        });
    </script>
</head>
<body class="min-h-screen text-gray-800 font-sans" style="background: linear-gradient(to bottom, #ffffff 0%, #f3f4f6 15%, #d1d5db 35%, #6b7280 55%, #374151 75%, #111827 100%);">
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NDZ9D5BK"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->

    @php $isHome = request()->routeIs('home'); @endphp
    <!-- Header -->
    <header
        x-data="{
            scrolled: false,
            mobileOpen: false,
            isHome: {{ $isHome ? 'true' : 'false' }},
        }"
        x-init="scrolled = window.pageYOffset > 20;
                window.addEventListener('scroll', () => scrolled = window.pageYOffset > 20, { passive: true })"
        :class="(isHome && !scrolled && !mobileOpen)
                    ? 'bg-transparent text-white border-transparent'
                    : 'bg-white/85 backdrop-blur-xl text-gray-800 border-amber-100/70 shadow-[0_10px_30px_-15px_rgba(120,80,180,0.15)]'"
        class="fixed top-0 inset-x-0 z-50 border-b transition-all duration-500"
    >
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                {{-- Logo --}}
                <a href="/" class="flex items-center gap-x-3 group">
                    <img
                        src="{{ asset('storage/images/sorenza-logo.jpg') }}"
                        alt="Sorenza logo"
                        width="48"
                        height="48"
                        class="h-[44px] w-[44px] rounded-full object-cover ring-1 ring-amber-200/60 transition-transform duration-500 group-hover:scale-105"
                        fetchpriority="high"
                    >
                    <div class="flex flex-col leading-none">
                        <span class="font-serif italic text-xl sm:text-2xl tracking-wide">Sorénza</span>
                        <span class="text-[9px] uppercase tracking-[0.35em] opacity-70 mt-0.5"
                              :class="(isHome && !scrolled && !mobileOpen) ? 'text-amber-200' : 'text-amber-700'">Maison de Parfum</span>
                    </div>
                </a>

                <div class="flex items-center gap-x-2 sm:gap-x-5">

                    <nav class="hidden md:flex items-center gap-x-1" role="navigation" aria-label="Glavna navigacija">
                        <a href="/shop"
                           class="px-4 py-2 text-xs font-medium uppercase tracking-[0.25em] transition-colors"
                           :class="(isHome && !scrolled) ? 'text-white/80 hover:text-amber-200' : 'text-gray-700 hover:text-amber-800'">
                            Kolekcija
                        </a>
                        <a href="{{ route('track.orders') }}"
                           class="px-4 py-2 text-xs font-medium uppercase tracking-[0.25em] transition-colors"
                           :class="(isHome && !scrolled) ? 'text-white/80 hover:text-amber-200' : 'text-gray-700 hover:text-amber-800'">
                            Prati narudžbu
                        </a>
                    </nav>

                    {{-- Divider --}}
                    <span class="hidden md:inline-block h-6 w-px"
                          :class="(isHome && !scrolled) ? 'bg-white/25' : 'bg-amber-200/70'"></span>

                    {{-- Cart --}}
                    <livewire:cart-counter />

                    {{-- Hamburger --}}
                    <button type="button"
                            @click="mobileOpen = !mobileOpen"
                            class="md:hidden relative p-2 rounded-full transition-colors duration-200"
                            :class="(isHome && !scrolled && !mobileOpen) ? 'hover:bg-white/10' : 'hover:bg-amber-50'"
                            aria-label="Otvori meni">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                            <path x-show="!mobileOpen" stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                            <path x-show="mobileOpen" stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Mobile menu --}}
            <div x-show="mobileOpen"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 -translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="md:hidden pb-5"
                 style="display: none;">
                <nav class="flex flex-col gap-1 pt-3 border-t border-amber-100" role="navigation" aria-label="Mobilna navigacija">
                    <a href="/shop"
                       class="mx-2 flex items-center gap-3 px-4 py-3 text-sm font-medium uppercase tracking-[0.2em] rounded-xl hover:bg-amber-50 transition-colors"
                       :class="(isHome && !scrolled) ? 'text-white hover:text-amber-800' : 'text-gray-700'">
                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.7">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                        Kolekcija
                    </a>
                    <a href="/cart"
                       class="mx-2 flex justify-between items-center px-4 py-3 text-sm font-medium uppercase tracking-[0.2em] rounded-xl hover:bg-amber-50 transition-colors"
                       :class="(isHome && !scrolled) ? 'text-white hover:text-amber-800' : 'text-gray-700'">
                        <span class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.112 11.213a.45.45 0 0 1-.447.494H4.232a.45.45 0 0 1-.447-.494l1.112-11.213a4.5 4.5 0 0 1 4.474-3.998h4.402a4.5 4.5 0 0 1 4.474 3.998Z"/>
                            </svg>
                            Korpa
                        </span>
                        <span class="bg-gradient-to-r from-[#BBA14F] to-[#DBC584] text-white text-[10px] px-2.5 py-0.5 rounded-full font-bold">
                            {{ count(session()->get('cart', [])) }}
                        </span>
                    </a>
                    <a href="{{ route('track.orders') }}"
                       class="mx-2 flex items-center gap-3 px-4 py-3 text-sm font-medium uppercase tracking-[0.2em] rounded-xl hover:bg-amber-50 transition-colors"
                       :class="(isHome && !scrolled) ? 'text-white hover:text-amber-800' : 'text-gray-700'">
                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.7">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
                        </svg>
                        Prati narudžbu
                    </a>
                </nav>
            </div>
        </div>
    </header>

    <!-- Main content -->
    <main role="main" class="{{ $isHome ? '' : 'pt-20 sm:pt-24' }}">
        @yield('content')

        @isset($slot)
        {{ $slot }}
        @endisset
    </main>
        <div
                x-data="{ show: false, message: '' }"
                x-on:notify.window="show = true; message = $event.detail; setTimeout(() => show = false, 3000)"
                x-show="show"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform translate-y-2"
                x-transition:enter-end="opacity-100 transform translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed bottom-5 right-5 z-[100] bg-gray-900 text-white px-6 py-3 rounded-xl shadow-2xl border border-white/10 flex items-center gap-3"
                style="display: none;"
            >
                <div class="bg-green-500 rounded-full p-1">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <span x-text="message" class="text-sm font-medium"></span>
        </div>

    <!-- Footer -->
    <footer class="relative mt-auto overflow-hidden text-gray-300"
            style="background:
                radial-gradient(circle at 15% 20%, rgba(155,110,180,0.30) 0%, transparent 55%),
                radial-gradient(circle at 85% 80%, rgba(217,119,87,0.25) 0%, transparent 55%),
                linear-gradient(160deg, #0f0f1e 0%, #16162b 50%, #0a0a15 100%);">

        {{-- Top ornamental border --}}
        <div aria-hidden="true" class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-amber-500/40 to-transparent"></div>

        <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 pt-16 sm:pt-20 pb-8">

            {{-- Brand pillar row --}}
            <div class="grid md:grid-cols-12 gap-10 md:gap-8 mb-14">

                {{-- Brand block (spans 5) --}}
                <div class="md:col-span-5">
                    <a href="/" class="inline-flex items-center gap-3 group">
                        <img src="{{ asset('storage/images/sorenza-logo.jpg') }}"
                             alt="Sorenza logo"
                             class="h-12 w-12 rounded-full object-cover ring-1 ring-amber-300/40">
                        <div class="flex flex-col leading-none">
                            <span class="font-serif italic text-2xl text-white tracking-wide">Sorénza</span>
                            <span class="text-[10px] uppercase tracking-[0.35em] text-amber-200/80 mt-0.5">Maison de Parfum</span>
                        </div>
                    </a>
                    <p class="mt-6 text-sm leading-relaxed text-gray-400 max-w-md">
                        Vaša premium online parfumerija.
                        <span class="text-amber-200/90">Ručno komponovani mirisi</span> inspirisani ikonama parfumerije —
                        brza dostava u BiH i regiji.
                    </p>

                    {{-- Social row --}}
                    <div class="mt-6 flex items-center gap-3">
                        <a href="https://www.instagram.com/sorenzaperfumes/" target="_blank" rel="noopener noreferrer" aria-label="Instagram"
                           class="inline-flex h-9 w-9 items-center justify-center rounded-full border border-white/15 text-gray-400 hover:text-white hover:border-amber-300/60 hover:bg-amber-500/10 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <rect x="3" y="3" width="18" height="18" rx="5"/>
                                <circle cx="12" cy="12" r="4"/>
                                <circle cx="17.5" cy="6.5" r="0.6" fill="currentColor"/>
                            </svg>
                        </a>
                        <a href="https://www.instagram.com/sorenzaperfumes/" target="_blank" rel="noopener noreferrer" aria-label="Facebook"
                           class="inline-flex h-9 w-9 items-center justify-center rounded-full border border-white/15 text-gray-400 hover:text-white hover:border-amber-300/60 hover:bg-amber-500/10 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M22 12c0-5.52-4.48-10-10-10S2 6.48 2 12c0 4.84 3.44 8.87 8 9.8V15H8v-3h2V9.5C10 7.57 11.57 6 13.5 6H16v3h-2c-.55 0-1 .45-1 1v2h3v3h-3v6.95c5.05-.5 9-4.76 9-9.95z"/>
                            </svg>
                        </a>
                    </div>
                </div>

                {{-- Kolekcija links --}}
                <div class="md:col-span-3">
                    <h3 class="text-[10px] uppercase tracking-[0.35em] text-amber-200/90 mb-5 flex items-center gap-2">
                        <span class="h-px w-5 bg-amber-400/60"></span>
                        Kolekcija
                    </h3>
                    <ul class="space-y-3 text-sm">
                        <li><a href="/shop" class="text-gray-400 hover:text-white transition-colors inline-flex items-center gap-2 group">
                            <span class="h-1 w-1 rounded-full bg-amber-500/40 group-hover:bg-amber-400 transition-colors"></span>
                            Svi parfemi
                        </a></li>
                        <li><a href="{{ route('shop', ['gender' => 'female']) }}" class="text-gray-400 hover:text-white transition-colors inline-flex items-center gap-2 group">
                            <span class="h-1 w-1 rounded-full bg-amber-500/40 group-hover:bg-amber-400 transition-colors"></span>
                            Ženski parfemi
                        </a></li>
                        <li><a href="{{ route('shop', ['gender' => 'male']) }}" class="text-gray-400 hover:text-white transition-colors inline-flex items-center gap-2 group">
                            <span class="h-1 w-1 rounded-full bg-amber-500/40 group-hover:bg-amber-400 transition-colors"></span>
                            Muški parfemi
                        </a></li>
                        <li><a href="{{ route('shop', ['gender' => 'unisex']) }}" class="text-gray-400 hover:text-white transition-colors inline-flex items-center gap-2 group">
                            <span class="h-1 w-1 rounded-full bg-amber-500/40 group-hover:bg-amber-400 transition-colors"></span>
                            Unisex
                        </a></li>
                    </ul>
                </div>

                {{-- Info + services --}}
                <div class="md:col-span-4">
                    <h3 class="text-[10px] uppercase tracking-[0.35em] text-amber-200/90 mb-5 flex items-center gap-2">
                        <span class="h-px w-5 bg-amber-400/60"></span>
                        Usluge & Kontakt
                    </h3>
                    <ul class="space-y-3.5 text-sm text-gray-400">
                        <li class="flex items-start gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="mt-0.5 h-4 w-4 text-amber-400/80 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 16V6a1 1 0 00-1-1H3v11h2m8 0H9m10 0h2v-6l-3-4h-5v4h6"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            Besplatna dostava iznad 120 KM
                        </li>
                        <li class="flex items-start gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="mt-0.5 h-4 w-4 text-amber-400/80 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 2M12 2a10 10 0 100 20 10 10 0 000-20z"/>
                            </svg>
                            Isporuka u 1-3 radna dana
                        </li>
                        <li class="flex items-start gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="mt-0.5 h-4 w-4 text-amber-400/80 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
                            </svg>
                            <a href="{{ route('track.orders') }}" class="hover:text-white transition-colors">Prati narudžbu</a>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="mt-0.5 h-4 w-4 text-amber-400/80 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            sorenzaperfumes@gmail.com
                        </li>
                        <li class="flex items-start gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="mt-0.5 h-4 w-4 text-amber-400/80 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z"/>
                                <circle cx="12" cy="11" r="3" stroke-width="1.6"/>
                            </svg>
                            Sarajevo, Bosna i Hercegovina
                        </li>
                    </ul>
                </div>
            </div>

            {{-- Divider with ornament --}}
            <div class="flex items-center justify-center gap-3 mb-8">
                <span class="h-px flex-1 bg-gradient-to-r from-transparent to-amber-500/30"></span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-amber-400/70" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 2l2 6 6 2-6 2-2 6-2-6-6-2 6-2z"/>
                </svg>
                <span class="h-px flex-1 bg-gradient-to-l from-transparent to-amber-500/30"></span>
            </div>

            {{-- Bottom row --}}
            <div class="flex flex-col md:flex-row items-center justify-between gap-4 text-center md:text-left">
                <p class="text-xs text-gray-500">
                    &copy; {{ date('Y') }} <span class="font-serif italic text-amber-200/80">Sorénza</span>. Sva prava zadržana.
                </p>
                <p class="text-[10px] uppercase tracking-[0.3em] text-gray-600">
                    Handcrafted with love in Sarajevo
                </p>
            </div>

            {{-- Hidden SEO text --}}
            <p class="sr-only">
                Sorenza parfumerija - Online prodavnica parfema | Muški parfemi | Ženski parfemi | Unisex parfemi | Parfemi Sarajevo | Luksuzni parfemi online | Kupovina parfema | Dostava parfema BiH
            </p>
        </div>
    </footer>
{{-- <script type="module" src="https://cdn.jsdelivr.net/npm/@tailwindplus/elements@1/dist/index.min.js"></script> --}}
@livewireScripts
</body>
</html>