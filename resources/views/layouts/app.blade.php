<!DOCTYPE html>
<html lang="bs" prefix="og: https://ogp.me/ns#">
<head>
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-NDZ9D5BK');</script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#7c3aed">

    {{-- 2. FAVICONS (Google Priority - Mora biti pri vrhu) --}}
    {{-- Glavna ikona za tabove i Google Search (48x48 multiple) --}}
    <link rel="icon" type="image/png" sizes="48x48" href="{{ asset('favicon-48x48.png') }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    
    {{-- Ostale rezolucije za uređaje --}}
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="icon" sizes="192x192" href="{{ asset('favicon-512x512.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">

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
        "logo": "{{ asset('images/logosorenza.webp') }}",
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
        "logo": "{{ asset('images/logo.png') }}",
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
</head>
<body class="min-h-screen text-gray-800 font-sans" style="background: linear-gradient(to bottom, #ffffff 0%, #f3f4f6 15%, #d1d5db 35%, #6b7280 55%, #374151 75%, #111827 100%);">
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NDZ9D5BK"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->

    <!-- Header -->
    <header class="bg-gradient-to-r from-white/95 via-white/90 to-white/95 backdrop-blur-xl shadow-lg sticky top-0 z-50 border-b border-gray-200/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <a href="/" class="text-2xl font-bold text-[#DAAA57] flex justify-center items-center gap-x-2">
                    <img 
                        src="{{ asset('storage/images/logosorenza.webp') }}" 
                        alt="logo"
                        width="28" 
                        height="45"
                        class="h-[45px] w-[28px] p-0"
                        fetchpriority="high"
                    >
                    <p class="text-xl mt-2 font-serif">Sorénza</p>
                </a>
    
                <div class="flex items-center gap-x-2 sm:gap-x-4">
                    
                    <livewire:cart-counter />
    
                    <nav class="hidden md:flex items-center space-x-1" role="navigation" aria-label="Glavna navigacija">
                        <a href="{{ route('track.orders') }}" class="text-sm font-medium text-gray-600 hover:text-[#9D683D] px-4 transition-colors">
                            Prati narudžbu
                        </a>
                        <div class="w-px h-6 bg-gray-300 mx-2"></div>
                        <a href="/shop" class="ml-2 px-5 py-2.5 bg-gradient-to-r from-[#3D2206] to-[#9D683D] text-white font-medium rounded-full shadow-md hover:shadow-lg hover:scale-105 transition-all duration-300 text-sm" title="Pogledajte našu kolekciju parfema">
                            Pogledaj Kolekciju
                        </a>
                    </nav>
    
                    <button type="button" class="md:hidden relative p-2 rounded-lg hover:bg-gray-100 transition-colors duration-200" onclick="document.getElementById('mobile-menu').classList.toggle('hidden')" aria-label="Otvori meni" aria-expanded="false">
                        <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
    
            <div id="mobile-menu" class="hidden md:hidden pb-4">
                <nav class="flex flex-col space-y-1 pt-2 border-t border-gray-200" role="navigation" aria-label="Mobilna navigacija">
                    <a href="/cart" class="mx-4 flex justify-between items-center px-5 py-3 text-gray-700 font-medium hover:bg-gray-50 rounded-lg transition-colors">
                        <span class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.112 11.213a.45.45 0 0 1-.447.494H4.232a.45.45 0 0 1-.447-.494l1.112-11.213a4.5 4.5 0 0 1 4.474-3.998h4.402a4.5 4.5 0 0 1 4.474 3.998Z" />
                            </svg>
                            Korpa
                        </span>
                        <span class="bg-[#DAAA57] text-white text-xs px-2.5 py-0.5 rounded-full font-bold">
                            {{ count(session()->get('cart', [])) }}
                        </span>
                    </a>
                    <a href="{{ route('track.orders') }}" class="mx-4 flex items-center px-5 py-3 text-gray-700 font-medium hover:bg-gray-50 rounded-lg transition-colors">
                        <span class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"></path></svg>
                            Prati narudžbu
                        </span>
                    </a>
                    <a href="/shop" class="mx-4 mt-2 px-5 py-3 bg-gradient-to-r from-[#3D2206] to-[#9D683D] text-white font-medium rounded-full shadow-md text-center text-sm">
                        Pogledaj Kolekciju
                    </a>
                </nav>
            </div>
        </div>
    </header>

    <!-- Main content -->
    <main role="main">
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
    <footer class="bg-gray-900 text-gray-200 py-12 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-3 gap-8 mb-8">
                {{-- About Section --}}
                <div>
                    <h3 class="text-white font-semibold text-lg mb-4">Sorenza Parfemi</h3>
                    <p class="text-gray-400 text-sm leading-relaxed">
                        Vaša premium online parfumerija za luksuzne parfeme i mirise. Nudimo širok izbor muških, ženskih i unisex parfema sa brzom dostavom u BiH. Kupite parfem online po povoljnim cijenama.
                    </p>
                </div>

                {{-- Quick Links --}}
                <div>
                    <h3 class="text-white font-semibold text-lg mb-4">Kategorije Parfema</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="/shop" class="text-gray-400 hover:text-white transition-colors" title="Svi parfemi - Online parfumerija">Svi Parfemi</a></li>
                        <li><a href="{{ route('shop', ['gender' => 'male']) }}" class="text-gray-400 hover:text-white transition-colors" title="Muški parfemi - Luksuzni mirisi za njega">Muški Parfemi</a></li>
                        <li><a href="{{ route('shop', ['gender' => 'female']) }}" class="text-gray-400 hover:text-white transition-colors" title="Ženski parfemi - Elegantni mirisi za nju">Ženski Parfemi</a></li>
                        <li><a href="{{ route('shop', ['gender' => 'unisex']) }}" class="text-gray-400 hover:text-white transition-colors" title="Unisex parfemi - Univerzalni mirisi">Unisex Parfemi</a></li>
                    </ul>
                </div>

                {{-- Contact & Delivery Info --}}
                <div>
                    <h3 class="text-white font-semibold text-lg mb-4">Dostava i Kontakt</h3>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li>🚚 Dostava: BiH</li>
                        <li>📦 Brza i sigurna dostava parfema</li>
                        {{-- <li>💳 Sigurno online plaćanje</li> --}}
                        {{-- <li>📞 Podrška: Pon-Pet 9-17h</li> --}}
                        <li>✉️ Kontaktirajte nas za pomoć</li>
                    </ul>
                </div>
            </div>

            {{-- SEO Footer Text --}}
            <div class="border-t border-gray-800 pt-6 mb-6">
                <p class="text-xs text-gray-200 leading-relaxed text-center">
                    Sorenza parfumerija - Online prodavnica parfema | Muški parfemi | Ženski parfemi | Unisex parfami | Parfemi Sarajevo | Luksuzni parfemi online | Kupovina parfema | Dostava parfema BiH
                </p>
            </div>

            <div class="border-t border-gray-800 pt-8 text-center">
                <p class="text-sm text-gray-200">&copy; {{ date('Y') }} Sorenza Parfemi. Sva prava zadržana. | Online parfumerija - Luksuzni parfemi BiH</p>
            </div>
        </div>
    </footer>
{{-- <script type="module" src="https://cdn.jsdelivr.net/npm/@tailwindplus/elements@1/dist/index.min.js"></script> --}}
@livewireScripts
</body>
</html>
