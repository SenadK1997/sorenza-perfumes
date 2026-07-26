<div class="min-h-[100vh]"
     style="background:
        radial-gradient(circle at 10% 8%, rgba(255,153,180,0.35) 0%, transparent 45%),
        radial-gradient(circle at 90% 12%, rgba(180,150,255,0.32) 0%, transparent 45%),
        radial-gradient(circle at 15% 92%, rgba(140,220,200,0.30) 0%, transparent 45%),
        radial-gradient(circle at 88% 88%, rgba(140,190,255,0.32) 0%, transparent 45%),
        radial-gradient(circle at 50% 50%, rgba(255,255,255,0.55) 0%, transparent 60%),
        linear-gradient(135deg, #fdf7fb 0%, #f4f0ff 50%, #eef7fb 100%);
        background-attachment: fixed;">
    <!-- Page header -->
    <div class="relative overflow-hidden border-b border-white/60">
        <div aria-hidden="true"
             class="pointer-events-none absolute inset-0"
             style="background: radial-gradient(70% 70% at 50% 0%, rgba(255,255,255,0.55) 0%, transparent 65%);">
        </div>
        <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-6 sm:py-8 text-center">
            <!-- Eyebrow with diamond glyphs -->
            <div class="flex items-center justify-center mb-3">
                <span class="h-px w-10 sm:w-12 bg-gradient-to-r from-transparent via-rose-300 to-amber-400"></span>
                <span class="mx-2 text-rose-400 text-[10px]">✦</span>
                <span class="text-[9px] sm:text-[10px] tracking-[0.45em] uppercase text-gray-500 font-light">Sorénza</span>
                <span class="mx-2 text-rose-400 text-[10px]">✦</span>
                <span class="h-px w-10 sm:w-12 bg-gradient-to-l from-transparent via-amber-400 to-rose-300"></span>
            </div>

            <!-- Fancy title -->
            <h1 class="shop-title font-serif italic font-light text-3xl sm:text-5xl leading-none tracking-tight">
                <span class="shop-title-shine bg-[linear-gradient(110deg,#1a1a2e_0%,#7a3fb0_25%,#d97757_45%,#e8b16b_50%,#d97757_55%,#7a3fb0_75%,#1a1a2e_100%)] bg-[length:200%_100%] bg-clip-text text-transparent">
                    Kolekcija
                </span>
            </h1>

            <!-- Script tagline -->
            <p class="mt-2 font-serif italic text-gray-500 text-xs sm:text-sm tracking-wide">
                Otkrijte svijet luksuznih mirisa
            </p>
        </div>
    </div>

    <style>
        @keyframes shopShine {
            0% { background-position: 200% center; }
            100% { background-position: -200% center; }
        }
        .shop-title-shine {
            animation: shopShine 8s linear infinite;
        }
        .shop-title {
            text-shadow: 0 4px 30px rgba(217, 119, 87, 0.08);
        }
    </style>

    <!-- MOBILE FILTERS -->
    <!-- Mobile filter toggle button -->
    <!-- Mobile filter dialog -->
    <div id="mobile-filters" class="fixed inset-0 z-50 hidden lg:hidden">
        <!-- backdrop -->
        <div class="fixed inset-0 bg-black/25" onclick="toggleMobileFilters(false)"></div>
    
        <!-- panel -->
        <div class="fixed right-0 top-0 flex h-full w-[50%] max-w-md flex-col bg-white shadow-2xl overflow-y-auto transition-transform transform translate-x-full" id="mobile-filters-panel">
        <div class="flex items-center justify-between p-5 border-b border-amber-100 bg-gradient-to-r from-white to-amber-50/50">
            <h2 class="text-sm font-semibold uppercase tracking-[0.25em] text-amber-800">Filteri</h2>
            <button type="button" aria-label="Zatvori filtere" class="inline-flex h-9 w-9 items-center justify-center rounded-full text-gray-500 hover:bg-[#BBA14F] hover:text-white transition-colors" onclick="toggleMobileFilters(false)">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
            </button>
        </div>
    
        <!-- Filters -->
        <div class="p-4 space-y-4">
            @if($showAvailabilityFilter)
                <fieldset class="pt-4">
                    <div class="mt-2">
                        <div class="flex items-center gap-2">
                            <input type="checkbox" wire:model.live="onlyAvailable" id="only-available-mobile" class="h-5 w-5 rounded border-gray-300 text-indigo-600">
                            <label for="only-available-mobile" class="text-sm text-gray-500">Prikaži dostupne</label>
                        </div>
                    </div>
                </fieldset>
            @endif
            <!-- Gender -->
            <fieldset>
            <legend class="text-xs font-semibold uppercase tracking-[0.2em] text-amber-800">Spol</legend>
            <div class="space-y-2 mt-3">
                <div class="flex items-center gap-2">
                <input type="checkbox" value="male" wire:model.live="gender" id="gender-male-mobile" class="h-5 w-5 rounded border-gray-300 checked:border-[#BBA14F] checked:bg-[#BBA14F]">
                <label for="gender-male-mobile" class="text-sm text-gray-600">Muški</label>
                </div>
                <div class="flex items-center gap-2">
                <input type="checkbox" value="female" wire:model.live="gender" id="gender-female-mobile" class="h-5 w-5 rounded border-gray-300 checked:border-[#BBA14F] checked:bg-[#BBA14F]">
                <label for="gender-female-mobile" class="text-sm text-gray-600">Ženski</label>
                </div>
                <div class="flex items-center gap-2">
                <input type="checkbox" value="unisex" wire:model.live="gender" id="gender-unisex-mobile" class="h-5 w-5 rounded border-gray-300 checked:border-[#BBA14F] checked:bg-[#BBA14F]">
                <label for="gender-unisex-mobile" class="text-sm text-gray-600">Unisex</label>
                </div>
            </div>
            </fieldset>

            <!-- Price -->
            <fieldset>
            <legend class="text-xs font-semibold uppercase tracking-[0.2em] text-amber-800">Cijena</legend>
            <div class="space-y-2 mt-3">
                @foreach([60, 80, 100, 120, 150] as $p)
                <div class="flex items-center gap-2">
                    <input type="checkbox" value="{{ $p }}" wire:model.live="price" id="price-{{ $p }}-mobile" class="h-5 w-5 rounded border-gray-300 checked:border-[#BBA14F] checked:bg-[#BBA14F]">
                    <label for="price-{{ $p }}-mobile" class="text-sm text-gray-600">do {{ $p }} KM</label>
                </div>
                @endforeach
            </div>
            </fieldset>
        </div>
        </div>
    </div>
    
    <!-- Toggle button -->
    <button class="lg:hidden inline-flex items-center gap-2 px-5 py-2.5 mt-8 ml-4 rounded-full border border-amber-800/60 bg-white/80 backdrop-blur text-sm font-medium uppercase tracking-[0.2em] text-amber-900 shadow-sm hover:bg-[#BBA14F] hover:text-white transition-colors" onclick="toggleMobileFilters(true)">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 4h18M6 12h12M10 20h4"/>
        </svg>
        Filteri
    </button>
  
    <!-- DESKTOP FILTERS + PRODUCTS -->
    <div class="mx-auto max-w-7xl px-4 py-4 lg:grid lg:grid-cols-4 lg:gap-x-8">
        <!-- Desktop filters -->
        <aside class="hidden lg:block lg:sticky lg:top-24 self-start space-y-7 rounded-3xl bg-white/70 backdrop-blur-md border border-white/80 shadow-[0_20px_60px_-30px_rgba(120,80,180,0.25)] p-7 relative overflow-hidden">
            <!-- Soft decorative glow -->
            <div aria-hidden="true"
                 class="pointer-events-none absolute -top-16 -right-16 h-40 w-40 rounded-full opacity-60 blur-3xl"
                 style="background: radial-gradient(circle, rgba(217,119,87,0.35) 0%, transparent 70%);"></div>
            <div aria-hidden="true"
                 class="pointer-events-none absolute -bottom-16 -left-16 h-40 w-40 rounded-full opacity-50 blur-3xl"
                 style="background: radial-gradient(circle, rgba(140,190,255,0.35) 0%, transparent 70%);"></div>

            <!-- Header with icon -->
            <div class="relative flex items-center justify-between pb-4 border-b border-gradient">
                <div class="flex items-center gap-3">
                    <span class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-gradient-to-br from-rose-100 to-amber-100 text-amber-700 shadow-sm ring-1 ring-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 4h18M6 12h12M10 20h4"/>
                        </svg>
                    </span>
                    <h2 class="font-serif italic text-lg text-gray-800 tracking-wide">Filteri</h2>
                </div>
                <span class="text-[10px] uppercase tracking-[0.25em] text-gray-400 font-light">Pretraga</span>
            </div>

            @if($showAvailabilityFilter)
                <fieldset class="relative">
                    <label for="only-available" class="group flex items-center gap-3 px-3 py-2.5 rounded-xl bg-gradient-to-r from-emerald-50/70 to-transparent hover:from-emerald-100/70 transition-colors cursor-pointer">
                        <input type="checkbox" wire:model.live="onlyAvailable" id="only-available" class="h-5 w-5 rounded-md border-gray-300 text-emerald-600 focus:ring-emerald-500">
                        <span class="text-sm text-gray-800 font-medium flex items-center gap-2">
                            <span class="inline-block h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            Prikaži samo dostupne
                        </span>
                    </label>
                </fieldset>
            @endif

            <!-- Gender -->
            <fieldset class="relative">
                <legend class="flex items-center gap-2 mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-rose-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <span class="text-[10px] font-semibold uppercase tracking-[0.3em] text-gray-600">Spol</span>
                    <span class="flex-1 h-px bg-gradient-to-r from-rose-200 via-purple-100 to-transparent ml-2"></span>
                </legend>
                <div class="space-y-1">
                    @php
                        $genderMeta = [
                            'male'   => ['label' => 'Muški',  'color' => '#046499', 'bg' => 'from-blue-50/70'],
                            'female' => ['label' => 'Ženski', 'color' => '#b22eae', 'bg' => 'from-pink-50/70'],
                            'unisex' => ['label' => 'Unisex', 'color' => '#318218', 'bg' => 'from-emerald-50/70'],
                        ];
                    @endphp
                    @foreach($genderMeta as $key => $meta)
                        <label for="gender-{{ $key }}"
                               class="group flex items-center gap-3 px-3 py-2 rounded-xl bg-gradient-to-r {{ $meta['bg'] }} to-transparent hover:to-white/50 transition-all cursor-pointer">
                            <input type="checkbox" value="{{ $key }}" wire:model.live="gender" id="gender-{{ $key }}"
                                   class="h-5 w-5 rounded-md border-gray-300"
                                   style="accent-color: {{ $meta['color'] }};">
                            <span class="text-sm text-gray-700 group-hover:text-gray-900 transition-colors flex items-center gap-2 font-medium">
                                <span class="inline-block h-2 w-2 rounded-full" style="background-color: {{ $meta['color'] }};"></span>
                                {{ $meta['label'] }}
                            </span>
                        </label>
                    @endforeach
                </div>
            </fieldset>

            <!-- Price -->
            <fieldset class="relative">
                <legend class="flex items-center gap-2 mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V6m0 10v2m0-2c-1.11 0-2.08-.402-2.599-1"/>
                        <circle cx="12" cy="12" r="9" stroke-width="2"/>
                    </svg>
                    <span class="text-[10px] font-semibold uppercase tracking-[0.3em] text-gray-600">Cijena</span>
                    <span class="flex-1 h-px bg-gradient-to-r from-amber-200 via-orange-100 to-transparent ml-2"></span>
                </legend>
                <div class="space-y-1">
                    @foreach([60, 80, 100, 120, 150] as $p)
                        <label for="price-{{ $p }}"
                               class="group flex items-center justify-between gap-3 px-3 py-2 rounded-xl hover:bg-amber-50/60 transition-colors cursor-pointer">
                            <div class="flex items-center gap-3">
                                <input type="checkbox" value="{{ $p }}" wire:model.live="price" id="price-{{ $p }}"
                                       class="h-5 w-5 rounded-md border-gray-300"
                                       style="accent-color: #BBA14F;">
                                <span class="text-sm text-gray-700 group-hover:text-gray-900 transition-colors font-medium">do {{ $p }} KM</span>
                            </div>
                            <span class="text-[10px] font-mono text-amber-500/70">≤{{ $p }}</span>
                        </label>
                    @endforeach
                </div>
            </fieldset>
        </aside>

        <!-- Perfume cards -->
        <div class="lg:col-span-3 flex flex-col">
            <div class="grid grid-cols-2 py-6 md:grid-cols-3 gap-3 sm:gap-6">
                @forelse($perfumes as $perfume)
                    @include('components.perfume-card', ['perfume' => $perfume])
                @empty
                    <div class="col-span-full flex flex-col items-center justify-center py-20 text-center text-gray-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-14 w-14 mb-4 text-amber-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z"/>
                        </svg>
                        <p class="text-lg font-light tracking-wide">Nema pronađenih parfema.</p>
                        <p class="text-sm mt-1 text-gray-400">Pokušajte prilagoditi filtere.</p>
                    </div>
                @endforelse
            </div>

            @if($perfumes->hasPages())
                <div class="mt-12 mb-10 flex flex-col items-center w-full">
                    <div class="flex items-center justify-center mb-6 w-full max-w-xs">
                        <span class="h-px flex-1 bg-gradient-to-r from-transparent to-amber-300"></span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="mx-3 h-4 w-4 text-amber-500" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2l1.5 5h5l-4 3 1.5 5-4-3-4 3 1.5-5-4-3h5z"/>
                        </svg>
                        <span class="h-px flex-1 bg-gradient-to-l from-transparent to-amber-300"></span>
                    </div>
                    <div class="px-2 w-full max-w-sm sm:max-w-md md:max-w-lg text-center overflow-x-auto">
                        {{ $perfumes->links() }}
                    </div>
                </div>
            @endif
        </div>
        <!-- Modal -->
        @if($showModal && $selectedPerfume)
            <div 
                x-data="{ open: @entangle('showModal') }"
                x-show="open"
                x-on:keydown.escape.window="open = false"
                class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
            >
                <div
                    @click.outside="$wire.closeQuickPreview()"
                    class="bg-white rounded-2xl shadow-2xl w-full max-w-3xl overflow-auto max-h-[90vh] p-6 relative ring-1 ring-amber-100"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                >

                    <button wire:click="closeQuickPreview"
                            aria-label="Zatvori"
                            class="absolute right-3 top-3 z-10 cursor-pointer inline-flex h-9 w-9 items-center justify-center rounded-full bg-gray-100 text-gray-600 hover:bg-[#BBA14F] hover:text-white shadow transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>

                    <div x-data="{ mainImage: '{{ Storage::url($selectedPerfume->main_image) }}' }" class="lg:grid lg:grid-cols-2 lg:gap-x-6">
                        <div>
                            <div class="aspect-square overflow-hidden rounded-2xl bg-white shadow-lg">
                                <img :src="mainImage" alt="{{ $selectedPerfume->name }}" class="h-full w-full object-cover transition-all duration-300">
                            </div>
                            <div class="mt-4 flex gap-2">
                                <button @click="mainImage='{{ Storage::url($selectedPerfume->main_image) }}'" class="h-16 w-16 rounded-xl border-2 border-gray-300 hover:border-indigo-500 overflow-hidden">
                                    <img src="{{ Storage::url($selectedPerfume->main_image) }}" class="h-full w-full object-cover">
                                </button>
                                @if($selectedPerfume->secondary_image)
                                    <button @click="mainImage='{{ Storage::url($selectedPerfume->secondary_image) }}'" class="h-16 w-16 rounded-xl border-2 border-gray-300 hover:border-indigo-500 overflow-hidden">
                                        <img src="{{ Storage::url($selectedPerfume->secondary_image) }}" class="h-full w-full object-cover">
                                    </button>
                                @endif
                            </div>
                        </div>

                        <div class="mt-4 lg:mt-0">
                            @php
                                    $parts = explode(' - ', $selectedPerfume->inspired_by);
                                    $genderColor = match($selectedPerfume->gender->value) {
                                    'male'   => '#046499',
                                    'female' => '#b22eae',
                                    'unisex' => '#318218',
                                    default  => '#000000',
                                };
                                @endphp
                                <h3 class="text-2xl font-bold tracking-tight" style="color: {{ $genderColor }};">
                                    {{ count($parts) === 2 ? "$parts[1] - $parts[0]" : $selectedPerfume->inspired_by }} <span class="font-light opacity-80">({{ $selectedPerfume->name }})</span>
                                </h3>
                                <div class="mt-2 h-px w-16 bg-gradient-to-r from-[#BBA14F] to-transparent"></div>

                            @if($selectedPerfume->description)
                                <p class="mt-4 text-gray-700">{{ $selectedPerfume->description }}</p>
                            @endif

                            @php
                                $accordConfig = config('accords');
                                $accordKeys = array_keys($accordConfig);
                                $accords = $selectedPerfume->accords ?? [];
                            @endphp

                            @if(count($accords) > 0)
                                <h2 class="mt-4 mb-2 font-semibold text-gray-900">Note:</h2>
                                <div class="flex flex-col gap-2">
                                    @foreach($accords as $accord)
                                        @php
                                            $index = $accord['name'];
                                            $name = $accordKeys[$index] ?? 'Unknown';
                                            $percentage = $accord['percentage'] ?? 0;
                                            $color = $accordConfig[$name] ?? '#6366F1';

                                            // LUMINANCE CALCULATION
                                            $hex = str_replace('#', '', $color);
                                            $r = hexdec(substr($hex, 0, 2));
                                            $g = hexdec(substr($hex, 2, 2));
                                            $b = hexdec(substr($hex, 4, 2));
                                            
                                            // This formula calculates how 'bright' a color is to the human eye
                                            $brightness = ($r * 299 + $g * 587 + $b * 114) / 1000;
                                            
                                            // If brightness is high (>180), use black text; otherwise use white.
                                            $textColor = ($brightness > 180) ? 'text-gray-900' : 'text-white';
                                        @endphp

                                        <div class="flex items-center gap-2">
                                            <div 
                                                class="h-6 rounded-md {{ $textColor }} text-xs font-bold flex items-center justify-center transition-all duration-500 shadow-sm"
                                                style="width: {{ $percentage }}%; background-color: {{ $color }};"
                                                title="{{ $name }}"
                                            >
                                                <span class="px-2 truncate">{{ $name }}</span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            <div class="mt-6 flex items-baseline gap-2">
                                <span class="text-xs uppercase tracking-[0.25em] text-gray-500">Cijena</span>
                                <span class="text-3xl font-semibold bg-gradient-to-r from-[#8b6914] to-[#BBA14F] bg-clip-text text-transparent">{{ number_format($selectedPerfume->price, 2) }} KM</span>
                            </div>

                            {{-- BUTTON LOGIC --}}
                            @if($selectedPerfume->is_available)
                                {{-- ACTIVE BUTTON --}}
                                <button
                                    wire:click="addToCart({{ $selectedPerfume->id }})"
                                    class="mt-4 w-full cursor-pointer px-5 py-3 bg-gradient-to-r from-[#BBA14F] to-[#DBC584] text-white font-bold uppercase tracking-[0.2em] rounded-full shadow-md transition-all duration-300 text-sm border border-transparent hover:from-white hover:to-white hover:border-[#BBA14F] hover:text-[#BBA14F]"
                                >
                                    Dodaj u korpu
                                </button>
                            @else
                                {{-- DISABLED BUTTON --}}
                                <button
                                    disabled
                                    class="mt-4 w-full px-5 py-3 bg-gray-100 text-gray-400 font-bold uppercase tracking-[0.2em] rounded-full text-sm border border-gray-200 cursor-not-allowed"
                                >
                                    Uskoro dostupno
                                </button>
                            @endif

                            <p class="mt-3 text-xs text-gray-500 flex items-center gap-1.5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#BBA14F]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16V6a1 1 0 00-1-1H3v11h2m8 0H9m10 0h2v-6l-3-4h-5v4h6"/>
                                </svg>
                                Besplatna dostava za narudžbe iznad 120 KM
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
    <script>
        function toggleMobileFilters(show) {
          const backdrop = document.getElementById('mobile-filters');
          const panel = document.getElementById('mobile-filters-panel');
          if(show){
            backdrop.classList.remove('hidden');
            panel.classList.remove('translate-x-full');
            panel.classList.add('translate-x-0');
          } else {
            panel.classList.remove('translate-x-0');
            panel.classList.add('translate-x-full');
            setTimeout(()=> backdrop.classList.add('hidden'), 300);
          }
        }
        </script>
        {{-- <script>
            Livewire.on('updateUrl', url => {
                history.replaceState(null, null, url);
            });
        </script> --}}
</div>
