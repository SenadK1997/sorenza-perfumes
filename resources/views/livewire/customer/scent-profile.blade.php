<div class="min-h-[80vh] px-4 py-10"
     style="background:
        radial-gradient(circle at 10% 10%, rgba(255,153,180,0.28) 0%, transparent 45%),
        radial-gradient(circle at 90% 20%, rgba(180,150,255,0.28) 0%, transparent 45%),
        linear-gradient(135deg, #fdf7fb 0%, #f4f0ff 50%, #eef7fb 100%);
        background-attachment: fixed;">
    <div class="mx-auto max-w-5xl">
        @include('livewire.customer.partials.header', ['customer' => $customer])

        @if($totalItems === 0)
            <div class="mt-8 rounded-3xl bg-white/80 backdrop-blur border border-white/90 shadow-xl p-10 text-center">
                <div class="mx-auto h-16 w-16 rounded-full bg-gradient-to-br from-rose-100 to-amber-100 flex items-center justify-center text-amber-800 shadow-inner">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M12 3l8 4v6c0 5-3.5 8-8 9-4.5-1-8-4-8-9V7l8-4z"/>
                    </svg>
                </div>
                <h2 class="mt-4 font-serif italic text-2xl text-gray-900">Vaš mirisni profil čeka svoj prvi trag</h2>
                <p class="mt-2 text-sm text-gray-500">Kad naručite svoj prvi parfem, ovdje ćemo prikazati vaš profil ukusa — omiljene note, spol i preporuke.</p>
                <a href="{{ route('shop') }}"
                   class="mt-6 inline-flex rounded-full bg-gradient-to-r from-[#8b6914] via-[#BBA14F] to-[#DBC584] px-6 py-2.5 text-xs font-semibold uppercase tracking-[0.28em] text-white shadow-lg hover:opacity-95 transition">
                    Otkrijte kolekciju
                </a>
            </div>
        @else
            {{-- Header stats --}}
            <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="rounded-2xl bg-white/80 backdrop-blur border border-white/90 p-5 shadow-sm">
                    <div class="text-[10px] uppercase tracking-[0.3em] text-gray-400">Kupljeno parfema</div>
                    <div class="mt-2 text-3xl font-semibold text-gray-900 tabular-nums">{{ $totalItems }}</div>
                </div>
                <div class="rounded-2xl bg-white/80 backdrop-blur border border-white/90 p-5 shadow-sm">
                    <div class="text-[10px] uppercase tracking-[0.3em] text-gray-400">Različitih parfema</div>
                    <div class="mt-2 text-3xl font-semibold text-gray-900 tabular-nums">{{ $uniquePerfumes }}</div>
                </div>
            </div>

            {{-- Top accords --}}
            @if(!empty($topAccords))
                <div class="mt-8 rounded-3xl bg-white/80 backdrop-blur border border-white/90 shadow-xl p-6 sm:p-8">
                    <div class="flex items-center gap-2 mb-5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v3m0 12v3M3 12h3m12 0h3M5.6 5.6l2.1 2.1m8.6 8.6l2.1 2.1M5.6 18.4l2.1-2.1m8.6-8.6l2.1-2.1"/>
                        </svg>
                        <h2 class="font-serif italic text-2xl text-gray-900">Vaše omiljene note</h2>
                    </div>

                    <div class="space-y-3">
                        @foreach($topAccords as $accord)
                            @php
                                $hex = str_replace('#', '', $accord['color']);
                                $r = hexdec(substr($hex, 0, 2));
                                $g = hexdec(substr($hex, 2, 2));
                                $b = hexdec(substr($hex, 4, 2));
                                $brightness = ($r * 299 + $g * 587 + $b * 114) / 1000;
                                $textColor = $brightness > 180 ? 'text-gray-900' : 'text-white';
                            @endphp
                            <div>
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-sm font-medium text-gray-700">{{ $accord['name'] }}</span>
                                    <span class="text-[11px] font-mono text-gray-400 tabular-nums">{{ number_format($accord['pct'], 0) }}%</span>
                                </div>
                                <div class="relative h-7 rounded-lg bg-gray-100 overflow-hidden">
                                    <div class="absolute inset-y-0 left-0 rounded-lg transition-all duration-700 flex items-center px-3"
                                         style="width: {{ max(6, $accord['pct']) }}%; background-color: {{ $accord['color'] }};">
                                        <span class="text-[10px] font-semibold uppercase tracking-widest {{ $textColor }}">{{ $accord['name'] }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Top inspirations --}}
            @if(!empty($topInspirations))
                <div class="mt-6 rounded-3xl bg-white/80 backdrop-blur border border-white/90 shadow-xl p-6 sm:p-7">
                    <h3 class="font-serif italic text-xl text-gray-900 mb-4">Omiljene inspiracije</h3>
                    <ol class="space-y-2">
                        @foreach($topInspirations as $insp => $count)
                            <li class="flex items-center justify-between gap-3 rounded-xl bg-amber-50/60 px-3 py-2">
                                <span class="text-sm text-gray-800">{{ $insp }}</span>
                                <span class="inline-flex items-center rounded-full bg-white text-amber-800 text-[10px] font-bold px-2 py-0.5 border border-amber-200">
                                    ×{{ $count }}
                                </span>
                            </li>
                        @endforeach
                    </ol>
                </div>
            @endif

            {{-- Recommended --}}
            @if($recommended->count() > 0)
                <div class="mt-6 rounded-3xl bg-white/80 backdrop-blur border border-white/90 shadow-xl p-6 sm:p-8">
                    <div class="flex items-center gap-2 mb-5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-fuchsia-500" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2l2.4 5 5.6.8-4 3.9.9 5.6L12 14.9 7.1 17.3l.9-5.6-4-3.9 5.6-.8L12 2z"/>
                        </svg>
                        <h3 class="font-serif italic text-2xl text-gray-900">Preporučeno za vas</h3>
                    </div>
                    <p class="text-xs text-gray-500 mb-5">Na osnovu vaše dominantne note <strong>{{ $topAccords[0]['name'] ?? '' }}</strong>.</p>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4">
                        @foreach($recommended as $p)
                            <x-perfume-card :perfume="$p" />
                        @endforeach
                    </div>
                </div>
            @endif
        @endif
    </div>
</div>
