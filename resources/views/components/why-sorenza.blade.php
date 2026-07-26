<section class="py-8 px-4 relative overflow-hidden">
    <div class="max-w-6xl mx-auto">
        {{-- Section header --}}
        <div class="text-center mb-14">
            <div class="flex items-center justify-center gap-3 mb-4">
                <span class="h-px w-10 bg-gradient-to-r from-transparent to-amber-400/70"></span>
                <span class="text-[10px] sm:text-xs uppercase tracking-[0.4em] text-amber-200/90 font-light">Otkrijte razliku</span>
                <span class="h-px w-10 bg-gradient-to-l from-transparent to-amber-400/70"></span>
            </div>
            <h2 class="font-serif italic font-light text-4xl md:text-5xl text-white tracking-tight">
                Zašto <span class="bg-[linear-gradient(110deg,#f8f0e0_0%,#f4d091_45%,#ffffff_50%,#f4d091_55%,#e8c789_100%)] bg-[length:200%_100%] bg-clip-text text-transparent">Sorénza</span>?
            </h2>
            <div class="mt-5 flex items-center justify-center gap-2">
                <span class="h-px w-12 bg-gradient-to-r from-transparent to-amber-400/60"></span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-amber-300" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 2l2 6 6 2-6 2-2 6-2-6-6-2 6-2z"/>
                </svg>
                <span class="h-px w-12 bg-gradient-to-l from-transparent to-amber-400/60"></span>
            </div>
        </div>

        {{-- Feature cards grid --}}
        <div class="grid md:grid-cols-3 gap-6 lg:gap-8">
            @php
                $features = [
                    [
                        'title' => 'Luksuzni sastojci',
                        'text'  => 'Pažljivo odabrane premium mirisne note iz cijelog svijeta, nabavljene od najfinijih parfemskih kuća.',
                        'svg'   => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="w-7 h-7"><path stroke-linecap="round" stroke-linejoin="round" d="M9 3v3H7a2 2 0 00-2 2v11a2 2 0 002 2h10a2 2 0 002-2V8a2 2 0 00-2-2h-2V3M9 3h6M9 3v3h6V3"/><circle cx="12" cy="14" r="3" stroke-width="1.5"/></svg>',
                    ],
                    [
                        'title' => 'Vrhunski kvalitet',
                        'text'  => 'Postojanost i intenzitet mirisa dostojni najskupljih originala — bez kompromisa.',
                        'svg'   => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="w-7 h-7"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15l-3.5 2 1-4-3-2.5 4-.3L12 6l1.5 4.2 4 .3-3 2.5 1 4z"/><circle cx="12" cy="12" r="9" stroke-width="1.5"/></svg>',
                    ],
                    [
                        'title' => 'Jedinstveni mirisi',
                        'text'  => 'Svaki miris priča priču — vašu priču. Prepoznatljive arome koje definišu ko ste vi.',
                        'svg'   => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="w-7 h-7"><path stroke-linecap="round" stroke-linejoin="round" d="M10 3h4v4h-2l-1 3h-2l-1-3H8V5a2 2 0 012-2zM7 10h10l1 10a1 1 0 01-1 1H7a1 1 0 01-1-1l1-10z"/></svg>',
                    ],
                ];
            @endphp
            @foreach($features as $f)
                <div class="group relative">
                    {{-- Soft glow --}}
                    <div class="absolute inset-0 rounded-3xl blur-2xl opacity-0 group-hover:opacity-60 transition-opacity duration-500 -z-10"
                         style="background: radial-gradient(circle at 50% 50%, rgba(244,208,145,0.35) 0%, transparent 70%);"></div>

                    {{-- Card --}}
                    <div class="relative h-full rounded-3xl bg-white/5 backdrop-blur-sm border border-white/10 p-8 transition-all duration-500 group-hover:bg-white/[0.08] group-hover:border-amber-200/30 group-hover:-translate-y-1.5 group-hover:shadow-2xl group-hover:shadow-amber-500/10">

                        {{-- Icon --}}
                        <div class="w-16 h-16 mx-auto mb-6 relative">
                            <div class="absolute inset-0 rounded-2xl bg-gradient-to-br from-amber-500/20 to-rose-500/20 group-hover:from-amber-400/30 group-hover:to-rose-400/30 transition-all duration-500"></div>
                            <div class="relative w-full h-full flex items-center justify-center text-amber-200 group-hover:text-amber-100 transition-colors duration-300">
                                {!! $f['svg'] !!}
                            </div>
                        </div>

                        <h3 class="text-lg font-serif italic text-white mb-3 text-center tracking-wide">{{ $f['title'] }}</h3>
                        <p class="text-gray-400 text-center text-sm leading-relaxed group-hover:text-gray-300 transition-colors duration-300">
                            {{ $f['text'] }}
                        </p>

                        {{-- Bottom accent --}}
                        <div class="mt-6 mx-auto h-px w-10 bg-gradient-to-r from-transparent via-amber-300/50 to-transparent group-hover:w-20 transition-all duration-500"></div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
