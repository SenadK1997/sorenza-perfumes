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
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('favicon.png') }}">

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
                <a href="/" class="text-2xl font-bold text-[#DAAA57] flex justify-center items-center gap-x-2">
                    <img 
                        src="{{ asset('storage/images/logosorenza.png') }}" 
                        alt="logo"
                        class="h-[45px] w-[35px] p-0"
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
    <footer class="bg-gray-900 text-gray-300 py-12 mt-auto">
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
                <p class="text-xs text-gray-600 leading-relaxed text-center">
                    Sorenza parfumerija - Online prodavnica parfema | Muški parfemi | Ženski parfemi | Unisex parfami | Parfemi Sarajevo | Luksuzni parfemi online | Kupovina parfema | Dostava parfema BiH
                </p>
            </div>

            <div class="border-t border-gray-800 pt-8 text-center">
                <p class="text-sm text-gray-500">&copy; {{ date('Y') }} Sorenza Parfemi. Sva prava zadržana. | Online parfumerija - Luksuzni parfemi BiH</p>
            </div>
        </div>
    </footer>
<script type="module" src="https://cdn.jsdelivr.net/npm/@tailwindplus/elements@1/dist/index.min.js"></script>
@livewireScripts
</body>
</html>
