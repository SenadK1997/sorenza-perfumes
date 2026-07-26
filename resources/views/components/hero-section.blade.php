<section class="hero relative overflow-hidden isolate"
         style="min-height: 92vh;
                background-image:
                    linear-gradient(to right, rgba(15,10,20,0.75) 0%, rgba(15,10,20,0.55) 35%, rgba(15,10,20,0.25) 60%, transparent 85%),
                    linear-gradient(to bottom, rgba(15,10,20,0.35) 0%, transparent 25%, transparent 65%, rgba(15,10,20,0.5) 100%),
                    url('{{ asset('storage/images/hero-sorenza.jpg') }}');
                background-size: cover;
                background-position: right center;">

    {{-- Grain / noise overlay for cinematic film feel --}}
    <div aria-hidden="true"
         class="pointer-events-none absolute inset-0 opacity-[0.15] mix-blend-overlay"
         style="background-image: url('data:image/svg+xml;utf8,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%22200%22 height=%22200%22><filter id=%22n%22><feTurbulence type=%22fractalNoise%22 baseFrequency=%220.9%22 numOctaves=%222%22 stitchTiles=%22stitch%22/></filter><rect width=%22100%25%22 height=%22100%25%22 filter=%22url(%23n)%22 opacity=%220.5%22/></svg>');">
    </div>

    {{-- Ornamental corner brackets (couture invitation feel) --}}
    <div aria-hidden="true" class="pointer-events-none absolute inset-6 sm:inset-10">
        <span class="absolute top-0 left-0 h-8 w-8 border-t border-l border-amber-200/40"></span>
        <span class="absolute top-0 right-0 h-8 w-8 border-t border-r border-amber-200/40"></span>
        <span class="absolute bottom-0 left-0 h-8 w-8 border-b border-l border-amber-200/40"></span>
        <span class="absolute bottom-0 right-0 h-8 w-8 border-b border-r border-amber-200/40"></span>
    </div>

    {{-- Content --}}
    <div class="relative z-10 mx-auto max-w-7xl px-6 sm:px-10 lg:px-16 h-full min-h-[92vh] flex items-center">
        <div class="max-w-2xl text-white opacity-0 animate-[heroReveal_1.4s_ease-out_0.2s_forwards]">

            {{-- EST. line --}}
            <div class="flex items-center gap-3 mb-8">
                <span class="h-px w-10 bg-amber-300/70"></span>
                <span class="text-[10px] sm:text-xs tracking-[0.45em] uppercase text-amber-200/90 font-light">
                    Est. Sorénza · Sarajevo
                </span>
            </div>

            {{-- Wordmark --}}
            <h1 class="hero-wordmark font-serif italic font-extralight leading-none tracking-tight text-white
                       text-[3.5rem] sm:text-8xl lg:text-9xl">
                <span class="hero-shine bg-[linear-gradient(110deg,#f8f0e0_0%,#f4d091_25%,#ffffff_45%,#f4d091_55%,#f8f0e0_75%,#e8c789_100%)] bg-[length:220%_100%] bg-clip-text text-transparent">
                    Sorénza
                </span>
            </h1>

            {{-- Divider with diamond --}}
            <div class="mt-8 flex items-center gap-3">
                <span class="h-px w-16 bg-gradient-to-r from-amber-300/80 to-transparent"></span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-amber-300" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 2l3 6 7 1-5 5 1 7-6-3-6 3 1-7-5-5 7-1z"/>
                </svg>
                <span class="h-px w-16 bg-gradient-to-l from-amber-300/80 to-transparent"></span>
            </div>

            {{-- Tagline (couture italic) --}}
            <p class="mt-8 font-serif italic text-lg sm:text-2xl text-white/95 leading-relaxed max-w-md tracking-wide">
                Nevidljiv detalj <br class="hidden sm:inline"/>koji čini razliku.
            </p>

            {{-- Sub tagline --}}
            <p class="mt-5 text-sm sm:text-base text-white/70 max-w-md leading-relaxed">
                Činimo luksuz pristupačnim. <br class="hidden sm:inline"/>
                <span class="text-amber-200/90">Ručno komponovani mirisi inspirisani ikonama parfumerije.</span>
            </p>

            {{-- CTA row --}}
            <div class="mt-12 flex flex-wrap items-center gap-6 sm:gap-10">
                <a href="/shop"
                   class="hero-cta group relative inline-flex items-center gap-3 px-8 py-4 border border-amber-200/80 rounded-full text-xs sm:text-sm font-medium uppercase tracking-[0.28em] text-white overflow-hidden transition-all duration-300 hover:text-gray-900">
                    <span class="relative z-10">Otkrijte kolekciju</span>
                    <svg class="relative z-10 h-3.5 w-3.5 transition-transform duration-300 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                    {{-- Fill sweep on hover --}}
                    <span aria-hidden="true" class="absolute inset-0 -translate-x-full bg-gradient-to-r from-amber-100 via-amber-200 to-amber-100 transition-transform duration-500 ease-out group-hover:translate-x-0"></span>
                </a>

                {{-- Understated text link --}}
                <a href="#brand-story"
                   class="group inline-flex items-center gap-2 text-xs sm:text-sm text-white/80 hover:text-amber-200 uppercase tracking-[0.28em] transition-colors">
                    Saznaj više
                    <span class="h-px w-8 bg-white/50 transition-all duration-300 group-hover:w-14 group-hover:bg-amber-200"></span>
                </a>
            </div>
        </div>
    </div>

    {{-- Scroll indicator --}}
    <div class="absolute bottom-6 left-1/2 -translate-x-1/2 z-10 flex flex-col items-center gap-2 text-white/60 opacity-0 animate-[heroReveal_1s_ease-out_1.5s_forwards]">
        <span class="text-[10px] tracking-[0.4em] uppercase">Scroll</span>
        <div class="h-8 w-px bg-gradient-to-b from-amber-200/70 to-transparent animate-[scrollLine_2s_ease-in-out_infinite]"></div>
    </div>
</section>

<style>
    @keyframes heroReveal {
        from { opacity: 0; transform: translateY(24px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    @keyframes heroShine {
        0%   { background-position: 220% center; }
        100% { background-position: -220% center; }
    }
    @keyframes scrollLine {
        0%   { transform: scaleY(0);   transform-origin: top; }
        45%  { transform: scaleY(1);   transform-origin: top; }
        55%  { transform: scaleY(1);   transform-origin: bottom; }
        100% { transform: scaleY(0);   transform-origin: bottom; }
    }
    .hero-shine {
        animation: heroShine 7s linear infinite;
        filter: drop-shadow(0 4px 24px rgba(244, 208, 145, 0.25));
    }
    .hero-wordmark {
        text-shadow: 0 6px 40px rgba(0,0,0,0.4);
    }
</style>
