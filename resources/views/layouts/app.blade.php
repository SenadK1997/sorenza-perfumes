<!DOCTYPE html>
<html lang="bs" prefix="og: https://ogp.me/ns#">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sorenza - Luksuzni Parfemi | Online Parfumerija BiH, Hrvatska, Srbija')</title>

    {{-- Primary SEO Meta Tags --}}
    <meta name="description" content="@yield('meta_description', 'Sorenza parfumerija - Kupite originalne luksuzne parfeme online. Širok izbor muških i ženskih parfema, toaletnih voda i mirisa poznatih svjetskih brendova. Brza dostava u Bosni i Hercegovini, Hrvatskoj i Srbiji. Povoljne cijene i akcije.')">
    <meta name="keywords" content="@yield('meta_keywords', 'parfemi, parfem, luksuzni parfemi, originalni parfemi, online parfumerija, kupovina parfema, parfemi online, mirisne note, toaletna voda, eau de parfum, eau de toilette, parfemi BiH, parfemi Sarajevo, parfemi Mostar, parfemi Banja Luka, parfemi Tuzla, parfemi Hrvatska, parfemi Zagreb, parfemi Split, parfemi Srbija, parfemi Beograd, parfemi Novi Sad, muški parfemi, ženski parfemi, unisex parfemi, brendirani parfemi, parfemi na akciji, povoljni parfemi, jeftini parfemi, ekskluzivni mirisi, arome, parfemska kolekcija, designer parfemi, niche parfemi, parfemi shop, prodaja parfema, naruči parfem, kupiti parfem online, best parfemi, popularni parfemi, novi parfemi 2024, parfemi cijena, parfem poklon, poklon set parfem, parfemi za nju, parfemi za njega, miris, mirisi, parfimeriјa, парфеми, парфем, мушки парфеми, женски парфеми')">
    <meta name="author" content="Sorenza">
    <meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
    <meta name="language" content="Bosnian, Croatian, Serbian">
    <meta name="geo.region" content="BA">
    <meta name="geo.placename" content="Bosnia and Herzegovina">
    <meta name="geo.position" content="43.8563;18.4131">
    <meta name="ICBM" content="43.8563, 18.4131">
    <meta name="coverage" content="Bosnia and Herzegovina, Croatia, Serbia">
    <meta name="target" content="all">
    <meta name="audience" content="all">
    <meta name="page-topic" content="Parfemi, Parfumerija, Mirisi, Kozmetika">
    <meta name="page-type" content="Online Shop">
    <meta name="classification" content="Business, Shopping, Parfemi, Kozmetika">

    {{-- Open Graph / Facebook --}}
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('og_title', 'Sorenza Parfemi - Kupite Luksuzne Parfeme Online | BiH, HR, SRB')">
    <meta property="og:description" content="@yield('og_description', 'Otkrijte kolekciju luksuznih parfema u Sorenza online parfumeriji. Originalni brendovi, jedinstveni mirisi, povoljne cijene i brza dostava širom BiH, Hrvatske i Srbije.')">
    <meta property="og:image" content="@yield('og_image', asset('images/sorenza-og.jpg'))">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:type" content="image/jpeg">
    <meta property="og:image:alt" content="Sorenza Parfemi - Luksuzni originalni parfemi online">
    <meta property="og:locale" content="bs_BA">
    <meta property="og:locale:alternate" content="hr_HR">
    <meta property="og:locale:alternate" content="sr_RS">
    <meta property="og:site_name" content="Sorenza Parfemi">

    {{-- Twitter --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('twitter_title', 'Sorenza - Luksuzni Parfemi')">
    <meta name="twitter:description" content="@yield('twitter_description', 'Premium parfemi i mirisi. Kupujte online - brza dostava u BiH, Hrvatskoj i Srbiji.')">
    <meta name="twitter:image" content="@yield('twitter_image', asset('images/sorenza-og.jpg'))">

    {{-- Additional SEO Meta Tags --}}
    <meta name="rating" content="general">
    <meta name="distribution" content="global">
    <meta name="revisit-after" content="3 days">
    <meta http-equiv="content-language" content="bs, hr, sr">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Sorenza Parfemi">
    <meta name="application-name" content="Sorenza Parfemi">
    <meta name="theme-color" content="#7c3aed">
    <meta name="msapplication-TileColor" content="#7c3aed">

    {{-- Canonical URL --}}
    <link rel="canonical" href="{{ url()->current() }}">

    {{-- Alternate Language Links for SEO --}}
    <link rel="alternate" hreflang="bs" href="{{ url()->current() }}">
    <link rel="alternate" hreflang="hr" href="{{ url()->current() }}">
    <link rel="alternate" hreflang="sr" href="{{ url()->current() }}">
    <link rel="alternate" hreflang="x-default" href="{{ url()->current() }}">

    {{-- Structured Data / Schema.org for Local Business --}}
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "Store",
        "name": "Sorenza Parfemi",
        "alternateName": ["Sorenza", "Sorenza Parfumerija", "Sorenza Online Shop"],
        "description": "Online prodavnica luksuznih i originalnih parfema. Širok izbor muških, ženskih i unisex mirisa. Dostava u BiH, Hrvatskoj i Srbiji.",
        "url": "{{ url('/') }}",
        "logo": "{{ asset('images/logo.png') }}",
        "image": "{{ asset('images/sorenza-og.jpg') }}",
        "priceRange": "$$",
        "currenciesAccepted": "BAM, EUR, RSD, HRK",
        "paymentAccepted": "Cash, Credit Card, Debit Card, Bank Transfer",
        "address": {
            "@@type": "PostalAddress",
            "addressCountry": "BA",
            "addressRegion": "Bosna i Hercegovina"
        },
        "areaServed": [
            {
                "@@type": "Country",
                "name": "Bosnia and Herzegovina",
                "alternateName": "Bosna i Hercegovina"
            },
            {
                "@@type": "Country",
                "name": "Croatia",
                "alternateName": "Hrvatska"
            },
            {
                "@@type": "Country",
                "name": "Serbia",
                "alternateName": "Srbija"
            }
        ],
        "hasOfferCatalog": {
            "@@type": "OfferCatalog",
            "name": "Parfemi Katalog",
            "itemListElement": [
                {
                    "@@type": "OfferCatalog",
                    "name": "Muški Parfemi",
                    "itemListElement": {
                        "@@type": "Offer",
                        "itemOffered": {
                            "@@type": "Product",
                            "name": "Muški parfemi"
                        }
                    }
                },
                {
                    "@@type": "OfferCatalog",
                    "name": "Ženski Parfemi",
                    "itemListElement": {
                        "@@type": "Offer",
                        "itemOffered": {
                            "@@type": "Product",
                            "name": "Ženski parfemi"
                        }
                    }
                },
                {
                    "@@type": "OfferCatalog",
                    "name": "Unisex Parfemi",
                    "itemListElement": {
                        "@@type": "Offer",
                        "itemOffered": {
                            "@@type": "Product",
                            "name": "Unisex parfemi"
                        }
                    }
                }
            ]
        },
        "sameAs": []
    }
    </script>

    {{-- Structured Data for Website --}}
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "WebSite",
        "name": "Sorenza",
        "alternateName": ["Sorenza Parfemi", "Sorenza Parfumerija", "Sorenza Online Parfumerija"],
        "url": "{{ url('/') }}",
        "inLanguage": ["bs", "hr", "sr"],
        "potentialAction": {
            "@@type": "SearchAction",
            "target": {
                "@@type": "EntryPoint",
                "urlTemplate": "{{ url('/shop') }}?search={search_term_string}"
            },
            "query-input": "required name=search_term_string"
        }
    }
    </script>

    {{-- Structured Data for Organization --}}
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "Organization",
        "name": "Sorenza",
        "legalName": "Sorenza Parfemi",
        "url": "{{ url('/') }}",
        "logo": "{{ asset('images/logo.png') }}",
        "description": "Sorenza - Premium online parfumerija sa širokim izborom luksuznih parfema i mirisa. Originalni parfemi, brza dostava, povoljne cijene.",
        "foundingDate": "2024",
        "slogan": "Luksuzni parfemi za svakoga",
        "knowsAbout": ["Parfemi", "Parfumerija", "Mirisi", "Toaletne vode", "Eau de Parfum", "Eau de Toilette", "Luksuzna kozmetika"],
        "makesOffer": {
            "@@type": "Offer",
            "itemOffered": {
                "@@type": "Product",
                "name": "Originalni parfemi",
                "category": "Parfemi i mirisi"
            }
        }
    }
    </script>

    {{-- BreadcrumbList Schema --}}
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "BreadcrumbList",
        "itemListElement": [
            {
                "@@type": "ListItem",
                "position": 1,
                "name": "Početna",
                "item": "{{ url('/') }}"
            },
            {
                "@@type": "ListItem",
                "position": 2,
                "name": "Parfemi",
                "item": "{{ url('/shop') }}"
            }
        ]
    }
    </script>

    {{-- FAQ Schema for common questions --}}
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "FAQPage",
        "mainEntity": [
            {
                "@@type": "Question",
                "name": "Gdje mogu kupiti originalne parfeme online u BiH?",
                "acceptedAnswer": {
                    "@@type": "Answer",
                    "text": "Originalne parfeme možete kupiti online u Sorenza parfumeriji. Nudimo širok izbor muških i ženskih parfema sa brzom dostavom u Bosni i Hercegovini."
                }
            },
            {
                "@@type": "Question",
                "name": "Da li Sorenza dostavlja u Hrvatsku i Srbiju?",
                "acceptedAnswer": {
                    "@@type": "Answer",
                    "text": "Da, Sorenza parfumerija dostavlja parfeme u Bosnu i Hercegovinu, Hrvatsku i Srbiju. Brza i sigurna dostava na vašu adresu."
                }
            },
            {
                "@@type": "Question",
                "name": "Koji parfemi su najpopularniji?",
                "acceptedAnswer": {
                    "@@type": "Answer",
                    "text": "U Sorenza parfumeriji možete pronaći najpopularnije muške i ženske parfeme svjetskih brendova. Pogledajte našu kolekciju luksuznih mirisa."
                }
            }
        ]
    }
    </script>

    @yield('structured_data')

    @vite('resources/css/app.css')
    @livewireStyles
</head>
<body class="min-h-screen text-gray-800 font-sans" style="background: linear-gradient(to bottom, #ffffff 0%, #f3f4f6 15%, #d1d5db 35%, #6b7280 55%, #374151 75%, #111827 100%);">

    <!-- Header -->
    <header class="bg-gradient-to-r from-white/95 via-white/90 to-white/95 backdrop-blur-xl shadow-lg sticky top-0 z-50 border-b border-gray-200/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <!-- Logo -->
                <a href="/" class="group flex items-center space-x-2" title="Sorenza - Luksuzni Parfemi">
                    <div class="relative">
                        <div class="absolute inset-0 bg-gradient-to-r from-amber-400 to-rose-500 rounded-full blur-md opacity-50 group-hover:opacity-75 transition-opacity duration-300"></div>
                        <div class="relative w-10 h-10 bg-gradient-to-br from-amber-500 via-rose-500 to-purple-600 rounded-full flex items-center justify-center shadow-lg">
                            <span class="text-white font-serif text-lg font-bold">S</span>
                        </div>
                    </div>
                    <span class="text-2xl font-serif font-bold bg-gradient-to-r from-gray-900 via-gray-700 to-gray-900 bg-clip-text text-transparent tracking-wide">Sorenza</span>
                </a>

                <!-- Desktop Navigation -->
                <nav class="hidden md:flex items-center space-x-1" role="navigation" aria-label="Glavna navigacija">
                    <a href="/" class="relative px-4 py-2 text-gray-700 font-medium group" title="Početna stranica">
                        <span class="relative z-10 group-hover:text-gray-900 transition-colors duration-300">Početna</span>
                        <span class="absolute inset-x-2 bottom-1 h-0.5 bg-gradient-to-r from-amber-400 to-rose-500 scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left rounded-full"></span>
                    </a>
                    <a href="/shop" class="relative px-4 py-2 text-gray-700 font-medium group" title="Kupite parfeme online">
                        <span class="relative z-10 group-hover:text-gray-900 transition-colors duration-300">Parfemi</span>
                        <span class="absolute inset-x-2 bottom-1 h-0.5 bg-gradient-to-r from-amber-400 to-rose-500 scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left rounded-full"></span>
                    </a>
                    {{-- <a href="/about" class="relative px-4 py-2 text-gray-700 font-medium group" title="O nama">
                        <span class="relative z-10 group-hover:text-gray-900 transition-colors duration-300">O Nama</span>
                        <span class="absolute inset-x-2 bottom-1 h-0.5 bg-gradient-to-r from-amber-400 to-rose-500 scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left rounded-full"></span>
                    </a>
                    <a href="/contact" class="relative px-4 py-2 text-gray-700 font-medium group" title="Kontaktirajte nas">
                        <span class="relative z-10 group-hover:text-gray-900 transition-colors duration-300">Kontakt</span>
                        <span class="absolute inset-x-2 bottom-1 h-0.5 bg-gradient-to-r from-amber-400 to-rose-500 scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left rounded-full"></span>
                    </a> --}}
                    <div class="w-px h-6 bg-gray-300 mx-2"></div>
                    <a href="/shop" class="ml-2 px-5 py-2.5 bg-gradient-to-r from-amber-500 via-rose-500 to-purple-600 text-white font-medium rounded-full shadow-md hover:shadow-lg hover:scale-105 transition-all duration-300 text-sm" title="Pogledajte našu kolekciju parfema">
                        Pogledaj Kolekciju
                    </a>
                </nav>

                <!-- Mobile Menu Button -->
                <button type="button" class="md:hidden relative p-2 rounded-lg hover:bg-gray-100 transition-colors duration-200" onclick="document.getElementById('mobile-menu').classList.toggle('hidden')" aria-label="Otvori meni" aria-expanded="false">
                    <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>

            <!-- Mobile Menu -->
            <div id="mobile-menu" class="hidden md:hidden pb-4">
                <nav class="flex flex-col space-y-1 pt-2 border-t border-gray-200" role="navigation" aria-label="Mobilna navigacija">
                    <a href="/" class="px-4 py-3 text-gray-700 hover:text-gray-900 hover:bg-gray-50 rounded-lg transition-colors duration-200 font-medium">Početna</a>
                    <a href="/shop" class="px-4 py-3 text-gray-700 hover:text-gray-900 hover:bg-gray-50 rounded-lg transition-colors duration-200 font-medium">Parfemi</a>
                    {{-- <a href="/about" class="px-4 py-3 text-gray-700 hover:text-gray-900 hover:bg-gray-50 rounded-lg transition-colors duration-200 font-medium">O Nama</a> --}}
                    {{-- <a href="/contact" class="px-4 py-3 text-gray-700 hover:text-gray-900 hover:bg-gray-50 rounded-lg transition-colors duration-200 font-medium">Kontakt</a> --}}
                    <a href="/shop" class="mx-4 mt-2 px-5 py-3 bg-gradient-to-r from-amber-500 via-rose-500 to-purple-600 text-white font-medium rounded-full shadow-md text-center text-sm">
                        Pogledaj Kolekciju
                    </a>
                </nav>
            </div>
        </div>
    </header>

    <!-- Main content -->
    <main role="main">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-300 py-12 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-3 gap-8 mb-8">
                {{-- About Section --}}
                <div>
                    <h3 class="text-white font-semibold text-lg mb-4">Sorenza Parfemi</h3>
                    <p class="text-gray-400 text-sm leading-relaxed">
                        Vaša premium online parfumerija za luksuzne parfeme i mirise. Nudimo širok izbor originalnih muških i ženskih parfema, toaletnih voda i eau de parfum sa brzom dostavom u BiH, Hrvatskoj i Srbiji. Kupite parfem online po povoljnim cijenama.
                    </p>
                </div>

                {{-- Quick Links --}}
                <div>
                    <h3 class="text-white font-semibold text-lg mb-4">Kategorije Parfema</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="/shop" class="text-gray-400 hover:text-white transition-colors" title="Svi parfemi - Online parfumerija">Svi Parfemi</a></li>
                        <li><a href="/shop?category=muski" class="text-gray-400 hover:text-white transition-colors" title="Muški parfemi - Luksuzni mirisi za njega">Muški Parfemi</a></li>
                        <li><a href="/shop?category=zenski" class="text-gray-400 hover:text-white transition-colors" title="Ženski parfemi - Elegantni mirisi za nju">Ženski Parfemi</a></li>
                        <li><a href="/shop?category=unisex" class="text-gray-400 hover:text-white transition-colors" title="Unisex parfemi - Univerzalni mirisi">Unisex Parfemi</a></li>
                        <li><a href="/shop?type=edp" class="text-gray-400 hover:text-white transition-colors" title="Eau de Parfum - Intenzivni mirisi">Eau de Parfum</a></li>
                        <li><a href="/shop?type=edt" class="text-gray-400 hover:text-white transition-colors" title="Eau de Toilette - Svježi mirisi">Eau de Toilette</a></li>
                    </ul>
                </div>

                {{-- Contact & Delivery Info --}}
                <div>
                    <h3 class="text-white font-semibold text-lg mb-4">Dostava i Kontakt</h3>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li>🚚 Dostava: BiH, Hrvatska, Srbija</li>
                        <li>📦 Brza i sigurna dostava parfema</li>
                        <li>💳 Sigurno online plaćanje</li>
                        <li>📞 Podrška: Pon-Pet 9-17h</li>
                        <li>✉️ Kontaktirajte nas za pomoć</li>
                    </ul>
                </div>
            </div>

            {{-- SEO Footer Text --}}
            <div class="border-t border-gray-800 pt-6 mb-6">
                <p class="text-xs text-gray-600 leading-relaxed text-center">
                    Sorenza parfumerija - Online prodavnica originalnih parfema | Muški parfemi | Ženski parfemi | Unisex mirisi | Eau de Parfum | Eau de Toilette | Toaletna voda | Parfemi Sarajevo | Parfemi Zagreb | Parfemi Beograd | Luksuzni parfemi online | Kupovina parfema | Dostava parfema BiH, Hrvatska, Srbija
                </p>
            </div>

            <div class="border-t border-gray-800 pt-8 text-center">
                <p class="text-sm text-gray-500">&copy; {{ date('Y') }} Sorenza Parfemi. Sva prava zadržana. | Online parfumerija - Luksuzni parfemi BiH, Hrvatska, Srbija</p>
            </div>
        </div>
    </footer>
<script type="module" src="https://cdn.jsdelivr.net/npm/@tailwindplus/elements@1/dist/index.min.js"></script>
@livewireScripts
</body>
</html>
