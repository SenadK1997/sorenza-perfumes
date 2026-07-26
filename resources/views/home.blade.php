@extends('layouts.app')

@section('title', 'Sorenza Parfemi | Luksuzni Parfemi Online - BiH')

@section('meta_description', 'Sorenza - Vaša premium online parfumerija. Kupite originalne luksuzne parfeme po povoljnim cijenama. Širok izbor muških, ženskih i unisex mirisa. Brza dostava u BiH. ✓ Originalni proizvodi ✓ Sigurna kupovina')

@section('meta_keywords', 'sorenza parfemi, luksuzni parfemi, originalni parfemi, parfemi online, parfumerija, parfemi BiH, muški parfemi, ženski parfemi, unisex parfemi, kupovina parfema online, brza dostava parfema')

@section('og_title', 'Sorenza Parfemi | Luksuzni Mirisi za Nju i Njega')
@section('og_description', 'Otkrijte svijet luksuznih mirisa u Sorenza parfumeriji. Originalni brendovi, jedinstvene arome i povoljne cijene. Brza dostava širom BiH 💎')
@section('og_image', asset('favicon.png'))

@section('twitter_title', 'Sorenza Parfemi | Premium Parfumerija Online')
@section('twitter_description', 'Luksuzni parfemi na jednom mjestu. Muški, ženski i unisex mirisi. Brza dostava u BiH, HR, SRB. ✨')
@section('twitter_image', asset('favicon.png'))

@section('content')
    {{-- 1. HERO --}}
    <x-hero-section />

    {{-- 2. TRUST STRIP --}}
    <section class="relative bg-white/70 backdrop-blur border-y border-amber-100/70">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-6 grid grid-cols-2 md:grid-cols-4 gap-6 sm:gap-4 text-center">
            <div class="flex flex-col items-center gap-2 text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#BBA14F]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16V6a1 1 0 00-1-1H3v11h2m8 0H9m10 0h2v-6l-3-4h-5v4h6"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <span class="text-xs uppercase tracking-[0.2em] font-medium">Besplatna dostava iznad 120 KM</span>
            </div>
            <div class="flex flex-col items-center gap-2 text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#BBA14F]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M12 3l8 4v6c0 5-3.5 8-8 9-4.5-1-8-4-8-9V7l8-4z"/>
                </svg>
                <span class="text-xs uppercase tracking-[0.2em] font-medium">Originalni sastojci</span>
            </div>
            <div class="flex flex-col items-center gap-2 text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#BBA14F]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 2M12 2a10 10 0 100 20 10 10 0 000-20z"/>
                </svg>
                <span class="text-xs uppercase tracking-[0.2em] font-medium">Isporuka 1-3 dana</span>
            </div>
            <div class="flex flex-col items-center gap-2 text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#BBA14F]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15a3 3 0 100-6 3 3 0 000 6zM19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 11-2.83 2.83l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 11-4 0v-.09a1.65 1.65 0 00-1-1.51 1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 11-2.83-2.83l.06-.06a1.65 1.65 0 00.33-1.82 1.65 1.65 0 00-1.51-1H3a2 2 0 110-4h.09a1.65 1.65 0 001.51-1 1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 112.83-2.83l.06.06a1.65 1.65 0 001.82.33h.01a1.65 1.65 0 001-1.51V3a2 2 0 114 0v.09a1.65 1.65 0 001 1.51h.01a1.65 1.65 0 001.82-.33l.06-.06a2 2 0 112.83 2.83l-.06.06a1.65 1.65 0 00-.33 1.82v.01a1.65 1.65 0 001.51 1H21a2 2 0 110 4h-.09a1.65 1.65 0 00-1.51 1z"/>
                </svg>
                <span class="text-xs uppercase tracking-[0.2em] font-medium">Sigurna kupovina</span>
            </div>
        </div>
    </section>

    {{-- 3. BRAND STORY --}}
    <section id="brand-story" class="relative py-16 sm:py-24 overflow-hidden scroll-mt-24">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 grid lg:grid-cols-2 gap-12 lg:gap-20 items-center">
            {{-- Image side --}}
            <div class="relative">
                <div class="absolute -inset-6 rounded-3xl bg-gradient-to-br from-rose-200/40 via-amber-100/40 to-transparent blur-2xl -z-10"></div>
                <div class="relative aspect-[4/5] rounded-3xl overflow-hidden shadow-2xl ring-1 ring-white/60">
                    <img src="{{ asset('storage/images/story-sorenza.jpg') }}"
                         alt="Sorenza priča"
                         loading="lazy"
                         decoding="async"
                         class="h-full w-full object-cover" />
                    <div class="absolute inset-0 bg-gradient-to-t from-black/30 via-transparent to-transparent"></div>
                    {{-- Signature stamp --}}
                    <div class="absolute bottom-6 left-6 text-white">
                        <div class="text-[10px] uppercase tracking-[0.35em] opacity-80">Est.</div>
                        <div class="font-serif italic text-2xl">Sorénza</div>
                    </div>
                </div>
            </div>

            {{-- Text side --}}
            <div>
                <div class="flex items-center gap-2 mb-4">
                    <span class="h-px w-8 bg-amber-500"></span>
                    <span class="text-[10px] uppercase tracking-[0.4em] text-amber-700 font-light">Naša priča</span>
                </div>
                <h2 class="font-serif italic font-light text-4xl sm:text-5xl leading-tight text-gray-900">
                    Umjetnost <br/>
                    <span class="bg-gradient-to-r from-gray-900 via-amber-700 to-gray-900 bg-clip-text text-transparent">nevidljivog detalja</span>
                </h2>
                <p class="mt-6 text-lg leading-relaxed text-gray-600">
                    Sorénza nastaje iz uvjerenja da je miris najintimniji potpis stila.
                    Naš tim spaja klasičnu parfemsku tradiciju s modernom senzibilnošću —
                    birajući samo najfinije mirisne note svijeta.
                </p>
                <p class="mt-4 text-base leading-relaxed text-gray-500">
                    Svaki flakon je pažljivo komponovan da izazove emociju, priču i sjećanje.
                    Nije parfem — to je vaš prvi utisak i posljednja rečenica koja ostaje u prostoru.
                </p>

                {{-- Micro stats --}}
                <div class="mt-10 grid grid-cols-3 gap-4">
                    <div class="text-center border-r border-amber-200/70 last:border-0">
                        <div class="font-serif text-3xl bg-gradient-to-r from-amber-700 to-[#BBA14F] bg-clip-text text-transparent">40+</div>
                        <div class="mt-1 text-[10px] uppercase tracking-[0.25em] text-gray-500">Mirisa</div>
                    </div>
                    <div class="text-center border-r border-amber-200/70">
                        <div class="font-serif text-3xl bg-gradient-to-r from-amber-700 to-[#BBA14F] bg-clip-text text-transparent">100%</div>
                        <div class="mt-1 text-[10px] uppercase tracking-[0.25em] text-gray-500">Original</div>
                    </div>
                    <div class="text-center">
                        <div class="font-serif text-3xl bg-gradient-to-r from-amber-700 to-[#BBA14F] bg-clip-text text-transparent">BiH</div>
                        <div class="mt-1 text-[10px] uppercase tracking-[0.25em] text-gray-500">Regija</div>
                    </div>
                </div>

                <a href="{{ route('shop') }}"
                   class="mt-10 inline-flex items-center gap-3 text-sm font-medium uppercase tracking-[0.25em] text-gray-900 hover:text-amber-800 transition-colors group">
                    Otkrijte kolekciju
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transition-transform group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>
            </div>
        </div>
    </section>

    {{-- 4. BESTSELLERS CAROUSEL --}}
    @if($bestsellers->count() > 0)
        <section class="relative py-16 sm:py-24 overflow-hidden"
                 style="background: linear-gradient(180deg, transparent 0%, rgba(255,245,235,0.55) 30%, rgba(255,245,235,0.55) 70%, transparent 100%);">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

                {{-- Header --}}
                <div class="text-center mb-12">
                    <div class="flex items-center justify-center gap-2 mb-3">
                        <span class="h-px w-10 bg-gradient-to-r from-transparent to-amber-500"></span>
                        <span class="text-rose-400 text-[10px]">✦</span>
                        <span class="text-[10px] uppercase tracking-[0.4em] text-amber-700 font-light">Najveći hitovi</span>
                        <span class="text-rose-400 text-[10px]">✦</span>
                        <span class="h-px w-10 bg-gradient-to-l from-transparent to-amber-500"></span>
                    </div>
                    <h2 class="font-serif italic font-light text-3xl sm:text-5xl text-gray-900">
                        <span class="bg-gradient-to-r from-gray-900 via-amber-800 to-gray-900 bg-clip-text text-transparent">Najprodavaniji</span>
                    </h2>
                    <p class="mt-3 text-sm text-gray-500 tracking-wide italic">
                        Mirisi koje najviše volite
                    </p>
                </div>

                {{-- Carousel --}}
                <div x-data="{
                        scrollTo(dir) {
                            const s = this.$refs.strip;
                            const card = s.querySelector('[data-card]');
                            const step = card ? card.offsetWidth + 24 : 300;
                            s.scrollBy({ left: dir * step, behavior: 'smooth' });
                        }
                     }"
                     class="relative">

                    {{-- Prev button --}}
                    <button @click="scrollTo(-1)"
                            aria-label="Prethodno"
                            class="hidden md:flex absolute -left-4 top-1/2 -translate-y-1/2 z-20 h-12 w-12 items-center justify-center rounded-full bg-white/90 backdrop-blur border border-amber-200/70 text-amber-800 shadow-lg hover:bg-gradient-to-r hover:from-[#BBA14F] hover:to-[#DBC584] hover:text-white hover:scale-110 transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </button>

                    {{-- Next button --}}
                    <button @click="scrollTo(1)"
                            aria-label="Sljedeće"
                            class="hidden md:flex absolute -right-4 top-1/2 -translate-y-1/2 z-20 h-12 w-12 items-center justify-center rounded-full bg-white/90 backdrop-blur border border-amber-200/70 text-amber-800 shadow-lg hover:bg-gradient-to-r hover:from-[#BBA14F] hover:to-[#DBC584] hover:text-white hover:scale-110 transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>

                    {{-- Scroll strip --}}
                    <div x-ref="strip"
                         class="flex gap-4 sm:gap-6 overflow-x-auto snap-x snap-mandatory scroll-smooth pb-6"
                         style="scrollbar-width: none; -ms-overflow-style: none;">
                        <style>[x-ref=strip]::-webkit-scrollbar{display:none;}</style>
                        @foreach($bestsellers as $index => $item)
                            @php
                                $itemParts = explode(' - ', $item->inspired_by);
                                $itemTitle = count($itemParts) === 2 ? "$itemParts[1] - $itemParts[0]" : $item->inspired_by;
                                $itemGenderColor = match($item->gender->value) {
                                    'male'   => '#046499',
                                    'female' => '#b22eae',
                                    'unisex' => '#318218',
                                    default  => '#000000',
                                };
                                $itemGenderLabel = match($item->gender->value) {
                                    'male'   => 'Muški',
                                    'female' => 'Ženski',
                                    'unisex' => 'Unisex',
                                    default  => '',
                                };
                            @endphp
                            <a href="{{ route('products.show', $item->id) }}"
                               data-card
                               class="group relative snap-start shrink-0 w-[calc(50%-0.5rem)] sm:w-[calc(50%-0.75rem)] md:w-[calc(33.333%-1rem)] lg:w-[calc(25%-1.125rem)] rounded-2xl bg-white/70 backdrop-blur border border-white/90 p-3 shadow-md hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 flex flex-col">
                                <div class="relative aspect-square overflow-hidden rounded-xl bg-gradient-to-br from-gray-50 to-gray-100">
                                    {{-- Rank badge (inside image bounds so scroll container never crops it) --}}
                                    <span class="absolute top-2 left-2 z-20 inline-flex h-8 w-8 items-center justify-center rounded-full bg-gradient-to-br from-[#8b6914] via-[#BBA14F] to-[#DBC584] text-white text-xs font-bold shadow-lg ring-2 ring-white">
                                        {{ $index + 1 }}
                                    </span>
                                    @if($itemGenderLabel)
                                        <span class="absolute right-2 top-2 z-20 inline-flex items-center justify-center rounded-full px-2.5 py-1 text-[9px] font-bold uppercase tracking-widest text-white shadow ring-1 ring-white/40"
                                              style="background-color: {{ $itemGenderColor }};">
                                            {{ $itemGenderLabel }}
                                        </span>
                                    @endif
                                    <img src="{{ Storage::url($item->main_image) }}"
                                         alt="{{ $item->name }}"
                                         loading="lazy"
                                         decoding="async"
                                         class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105" />
                                    <div class="absolute inset-x-0 bottom-0 h-16 bg-gradient-to-t from-black/30 to-transparent"></div>
                                </div>
                                <h3 class="mt-3 text-sm sm:text-base font-bold line-clamp-2 min-h-[2.5rem] leading-tight" style="color: {{ $itemGenderColor }};">
                                    {{ $itemTitle }}
                                </h3>
                                <p class="mt-1 text-base sm:text-lg font-semibold bg-gradient-to-r from-[#8b6914] to-[#BBA14F] bg-clip-text text-transparent">
                                    {{ number_format($item->price, 2) }} KM
                                </p>
                            </a>
                        @endforeach
                    </div>
                </div>

                <div class="mt-12 text-center">
                    <a href="{{ route('shop') }}"
                       class="group relative inline-flex items-center gap-3 rounded-full border border-amber-800/70 bg-white/80 backdrop-blur px-8 py-3.5 text-xs sm:text-sm font-medium uppercase tracking-[0.28em] text-amber-900 shadow-md hover:bg-gradient-to-r hover:from-[#BBA14F] hover:to-[#DBC584] hover:text-white hover:border-transparent transition-all">
                        Pregledaj cijelu kolekciju
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transition-transform group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </a>
                </div>
            </div>
        </section>
    @endif

    {{-- 5. COLLECTIONS BY GENDER --}}
    <x-gender-sales />

    {{-- 6. WHY SORENZA (dark editorial section) --}}
    <div class="relative py-16 sm:py-24 overflow-hidden"
         style="background:
            radial-gradient(circle at 20% 30%, rgba(155,110,180,0.35) 0%, transparent 50%),
            radial-gradient(circle at 80% 70%, rgba(217,119,87,0.30) 0%, transparent 50%),
            linear-gradient(160deg, #1a1a2e 0%, #16213e 50%, #0f0f1e 100%);">
        <x-why-sorenza />
    </div>

    {{-- 7. TESTIMONIALS --}}
    <section class="relative py-16 sm:py-24 overflow-hidden">
        <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-14">
                <div class="flex items-center justify-center gap-2 mb-3">
                    <span class="h-px w-8 bg-gradient-to-r from-transparent to-amber-500"></span>
                    <span class="text-[10px] uppercase tracking-[0.4em] text-amber-700 font-light">Iskustva</span>
                    <span class="h-px w-8 bg-gradient-to-l from-transparent to-amber-500"></span>
                </div>
                <h2 class="font-serif italic font-light text-3xl sm:text-5xl text-gray-900">
                    Riječi naših klijenata
                </h2>
            </div>

            <div class="grid md:grid-cols-3 gap-6 sm:gap-8">
                @foreach([
                    ['name' => 'Amina H.', 'role' => 'Sarajevo', 'text' => 'Mirisi su nevjerovatno postojani, cijeli dan me prati kompliment. Sorénza je moje novo otkriće.'],
                    ['name' => 'Emir K.', 'role' => 'Tuzla', 'text' => 'Kvaliteta na nivou originalnih parfema, a cijena mnogo pristupačnija. Preporučujem svima.'],
                    ['name' => 'Selma D.', 'role' => 'Mostar', 'text' => 'Elegantno pakovanje, brza dostava i miris koji izaziva emocije. Naručit ću opet.'],
                ] as $t)
                    <figure class="relative rounded-2xl bg-white/70 backdrop-blur border border-white/80 p-8 shadow-md hover:shadow-xl transition-shadow">
                        <svg xmlns="http://www.w3.org/2000/svg" class="absolute -top-4 left-6 h-10 w-10 text-amber-300" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M9 7H5a2 2 0 00-2 2v4a2 2 0 002 2h4v-4H6c0-1.5.5-2 3-2V7zm10 0h-4a2 2 0 00-2 2v4a2 2 0 002 2h4v-4h-3c0-1.5.5-2 3-2V7z"/>
                        </svg>
                        <blockquote class="text-gray-700 leading-relaxed italic">
                            "{{ $t['text'] }}"
                        </blockquote>
                        <figcaption class="mt-6 flex items-center gap-3">
                            <div class="h-10 w-10 rounded-full bg-gradient-to-br from-amber-200 to-rose-200 flex items-center justify-center text-amber-800 font-bold">
                                {{ mb_substr($t['name'], 0, 1) }}
                            </div>
                            <div>
                                <div class="font-semibold text-gray-900">{{ $t['name'] }}</div>
                                <div class="text-xs text-gray-500 uppercase tracking-widest">{{ $t['role'] }}</div>
                            </div>
                        </figcaption>
                    </figure>
                @endforeach
            </div>
        </div>
    </section>

    {{-- 8. PARTNER / NEWSLETTER --}}
    <x-become-partner />
@endsection
