<div class="min-h-[100vh]"
     style="background:
        radial-gradient(circle at 10% 8%, rgba(255,153,180,0.35) 0%, transparent 45%),
        radial-gradient(circle at 90% 12%, rgba(180,150,255,0.32) 0%, transparent 45%),
        radial-gradient(circle at 15% 92%, rgba(140,220,200,0.30) 0%, transparent 45%),
        radial-gradient(circle at 88% 88%, rgba(140,190,255,0.32) 0%, transparent 45%),
        radial-gradient(circle at 50% 50%, rgba(255,255,255,0.55) 0%, transparent 60%),
        linear-gradient(135deg, #fdf7fb 0%, #f4f0ff 50%, #eef7fb 100%);
        background-attachment: fixed;">
    <div class="mx-auto max-w-7xl px-4 py-6 sm:py-10 lg:px-8">

        @php
            $parts = explode(' - ', $perfume->inspired_by);
            $displayTitle = count($parts) === 2 ? "$parts[1] - $parts[0]" : $perfume->inspired_by;
            $genderColor = match($perfume->gender->value) {
                'male'   => '#046499',
                'female' => '#b22eae',
                'unisex' => '#318218',
                default  => '#000000',
            };
            $genderLabel = match($perfume->gender->value) {
                'male'   => 'Muški',
                'female' => 'Ženski',
                'unisex' => 'Unisex',
                default  => '',
            };
            $accordConfig = config('accords');
            $accordKeys = array_keys($accordConfig);
            $accords = $perfume->accords ?? [];
        @endphp

        <!-- Breadcrumb -->
        <nav aria-label="Breadcrumb" class="mb-6 flex items-center text-xs text-gray-500 tracking-wide">
            <a href="{{ route('home') }}" class="hover:text-fuchsia-600 transition-colors uppercase tracking-[0.15em]">Početna</a>
            <span class="mx-2 text-fuchsia-400">/</span>
            <a href="{{ route('shop') }}" class="hover:text-fuchsia-600 transition-colors uppercase tracking-[0.15em]">Kolekcija</a>
            <span class="mx-2 text-fuchsia-400">/</span>
            <span class="truncate text-gray-700 uppercase tracking-[0.15em]">{{ $displayTitle }}</span>
        </nav>

        <div
            class="lg:grid lg:grid-cols-2 lg:gap-x-16 rounded-3xl bg-white/70 backdrop-blur-md border border-white/80 shadow-[0_20px_60px_-30px_rgba(160,60,180,0.30)] p-4 sm:p-8"
            x-data="{
                mainImage: '{{ asset('storage/' . $perfume->main_image) }}',
                zoomed: false
            }"
        >

            <!-- IMAGE GALLERY -->
            <div>
                <!-- Main image -->
                <div class="relative aspect-square overflow-hidden max-h-[560px] rounded-2xl bg-gradient-to-br from-gray-50 to-gray-100 shadow-inner ring-1 ring-white/60">
                    @if($genderLabel)
                        <span class="absolute left-4 top-4 z-10 inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-[10px] font-bold uppercase tracking-widest text-white shadow-md ring-1 ring-white/40"
                              style="background-color: {{ $genderColor }};">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="12" r="6"/></svg>
                            {{ $genderLabel }}
                        </span>
                    @endif
                    @if(!$perfume->is_available)
                        <span class="absolute right-4 top-4 z-10 rounded-full bg-red-600/95 px-3 py-1 text-[10px] font-bold uppercase tracking-widest text-white shadow-md">
                            Uskoro
                        </span>
                    @elseif($perfume->is_on_sale)
                        <span class="absolute right-4 top-4 z-10 rounded-full bg-gradient-to-r from-rose-500 to-red-600 px-3 py-1 text-[10px] font-bold uppercase tracking-widest text-white shadow-md ring-1 ring-white/40">
                            Akcija −{{ (int) $perfume->discount_percentage }}%
                        </span>
                    @endif
                    <img
                        :src="mainImage"
                        alt="{{ $perfume->name }}"
                        decoding="async"
                        fetchpriority="high"
                        class="h-full w-full object-cover transition-transform duration-500"
                        :class="zoomed ? 'scale-110' : 'scale-100'"
                        @mouseenter="zoomed = true"
                        @mouseleave="zoomed = false"
                    >
                </div>

                <!-- Thumbnails -->
                <div class="mt-5 flex gap-3">
                    <button
                        @click="mainImage = '{{ asset('storage/' . $perfume->main_image) }}'"
                        :class="mainImage === '{{ asset('storage/' . $perfume->main_image) }}' ? 'border-fuchsia-400 ring-2 ring-fuchsia-400/30' : 'border-gray-200 hover:border-fuchsia-400/60'"
                        class="h-20 w-20 overflow-hidden rounded-xl border-2 transition-all"
                    >
                        <img src="{{ asset('storage/' . $perfume->main_image) }}" class="h-full w-full object-cover">
                    </button>

                    @if($perfume->secondary_image)
                        <button
                            @click="mainImage = '{{ asset('storage/' . $perfume->secondary_image) }}'"
                            :class="mainImage === '{{ asset('storage/' . $perfume->secondary_image) }}' ? 'border-fuchsia-400 ring-2 ring-fuchsia-400/30' : 'border-gray-200 hover:border-fuchsia-400/60'"
                            class="h-20 w-20 overflow-hidden rounded-xl border-2 transition-all"
                        >
                            <img src="{{ asset('storage/' . $perfume->secondary_image) }}" class="h-full w-full object-cover">
                        </button>
                    @endif
                </div>
            </div>

            <!-- PERFUME INFO -->
            <div class="mt-10 lg:mt-0">
                <!-- Eyebrow -->
                <div class="flex items-center gap-2 mb-3">
                    <span class="h-px w-6 bg-gradient-to-r from-transparent to-amber-500"></span>
                    <span class="text-[10px] tracking-[0.35em] uppercase text-amber-700 font-light">Sorénza Parfum</span>
                </div>

                <h1 class="text-3xl sm:text-4xl font-bold tracking-tight leading-tight" style="color: {{ $genderColor }};">
                    {{ $displayTitle }}
                    <span class="block mt-1 text-lg font-light opacity-80">({{ $perfume->name }})</span>
                </h1>

                <div class="mt-4 h-px w-20 bg-gradient-to-r from-fuchsia-400 via-rose-300 to-transparent"></div>

                @if($perfume->description)
                    <p class="mt-6 text-base sm:text-lg leading-relaxed text-gray-600 italic">
                        {{ $perfume->description }}
                    </p>
                @endif

                @if(count($accords) > 0)
                    <div class="mt-8">
                        <div class="flex items-center gap-2 mb-4">
                            <span class="h-px w-4 bg-amber-500"></span>
                            <h2 class="text-xs font-semibold uppercase tracking-[0.25em] text-amber-800">Note mirisa</h2>
                        </div>

                        <div class="flex flex-col gap-2.5">
                            @foreach($accords as $accord)
                                @php
                                    $index = $accord['name'];
                                    $name = $accordKeys[$index] ?? 'Unknown';
                                    $percentage = $accord['percentage'] ?? 0;
                                    $color = $accordConfig[$name] ?? '#6366F1';

                                    $hex = str_replace('#', '', $color);
                                    $r = hexdec(substr($hex, 0, 2));
                                    $g = hexdec(substr($hex, 2, 2));
                                    $b = hexdec(substr($hex, 4, 2));
                                    $brightness = ($r * 299 + $g * 587 + $b * 114) / 1000;
                                    $textColor = ($brightness > 180) ? 'text-gray-900' : 'text-white';
                                @endphp

                                <div class="flex items-center gap-3">
                                    <div
                                        class="h-7 rounded-md {{ $textColor }} text-xs font-bold flex items-center justify-between px-3 transition-all duration-500 shadow-sm"
                                        style="width: {{ $percentage }}%; background-color: {{ $color }};"
                                        title="{{ $name }} — {{ $percentage }}%"
                                    >
                                        <span class="truncate">{{ $name }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Price + wishlist -->
                <div class="mt-8 flex items-center justify-between gap-3">
                    <div class="flex items-baseline gap-3 flex-wrap">
                        <span class="text-xs uppercase tracking-[0.3em] text-gray-500">Cijena</span>
                        @if($perfume->is_on_sale)
                            <span class="text-4xl font-semibold bg-gradient-to-r from-rose-600 via-red-500 to-orange-500 bg-clip-text text-transparent">
                                {{ number_format($perfume->price, 2) }} KM
                            </span>
                            <span class="text-lg font-medium text-gray-400 line-through">
                                {{ number_format($perfume->original_price, 2) }} KM
                            </span>
                            <span class="inline-flex items-center rounded-full bg-rose-100 text-rose-700 text-[10px] font-bold uppercase tracking-widest px-2 py-0.5">
                                Ušteda {{ number_format($perfume->original_price - $perfume->price, 2) }} KM
                            </span>
                        @else
                            <span class="text-4xl font-semibold bg-gradient-to-r from-violet-600 via-fuchsia-500 to-rose-500 bg-clip-text text-transparent">
                                {{ number_format($perfume->price, 2) }} KM
                            </span>
                        @endif
                    </div>
                    <div wire:ignore>
                        <livewire:wishlist-button :perfume-id="$perfume->id" size="lg" :key="'wl-detail-'.$perfume->id" />
                    </div>
                </div>

                <!-- Divider -->
                <div class="my-6 h-px w-full bg-gradient-to-r from-fuchsia-200 via-violet-100 to-transparent"></div>

                <!-- CTA -->
                @if($perfume->is_available)
                    <button
                        wire:click="addToCart({{ $perfume->id }})"
                        class="group w-full cursor-pointer relative overflow-hidden px-6 py-4 bg-gradient-to-r from-violet-600 via-fuchsia-500 to-rose-500 text-white font-bold uppercase tracking-[0.25em] rounded-full shadow-[0_10px_25px_-8px_rgba(190,80,180,0.55)] hover:shadow-[0_18px_40px_-10px_rgba(220,60,140,0.7)] hover:-translate-y-0.5 hover:from-violet-500 hover:via-fuchsia-400 hover:to-rose-400 transition-all duration-300 text-sm border border-white/30"
                    >
                        <span class="relative z-10 inline-flex items-center justify-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            Dodaj u korpu
                        </span>
                        <span aria-hidden="true" class="pointer-events-none absolute inset-0 -translate-x-full bg-[linear-gradient(120deg,transparent_30%,rgba(255,255,255,0.35)_50%,transparent_70%)] transition-transform duration-700 ease-out group-hover:translate-x-full"></span>
                    </button>
                @else
                    <button
                        disabled
                        class="w-full px-6 py-4 bg-gray-100 text-gray-400 font-bold uppercase tracking-[0.25em] rounded-full text-sm border border-gray-200 cursor-not-allowed"
                    >
                        Uskoro dostupno
                    </button>
                @endif

                <!-- Feature bullets -->
                <div class="mt-6 grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div class="flex items-center gap-2 text-xs text-gray-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-fuchsia-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16V6a1 1 0 00-1-1H3v11h2m8 0H9m10 0h2v-6l-3-4h-5v4h6"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        {{ \App\Services\ShippingCalculator::summaryLabel() }}
                    </div>
                    <div class="flex items-center gap-2 text-xs text-gray-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-fuchsia-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M12 3l8 4v6c0 5-3.5 8-8 9-4.5-1-8-4-8-9V7l8-4z"/>
                        </svg>
                        Originalni sastojci
                    </div>
                    <div class="flex items-center gap-2 text-xs text-gray-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-fuchsia-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 2M12 2a10 10 0 100 20 10 10 0 000-20z"/>
                        </svg>
                        Brza isporuka 1-3 dana
                    </div>
                </div>
            </div>

        </div>

        {{-- Related perfumes --}}
        @if(isset($related) && $related->count() > 0)
            <section class="mt-16 sm:mt-24">
                <div class="text-center mb-10">
                    <div class="flex items-center justify-center gap-2 mb-3">
                        <span class="h-px w-10 bg-gradient-to-r from-transparent to-fuchsia-400"></span>
                        <span class="text-fuchsia-400 text-[10px]">✦</span>
                        <span class="h-px w-10 bg-gradient-to-l from-transparent to-fuchsia-400"></span>
                    </div>
                    <h2 class="font-serif italic font-light text-3xl sm:text-4xl text-gray-900">
                        Možda će Vam se svidjeti
                    </h2>
                </div>

                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                    @foreach($related as $perfume)
                        <x-perfume-card :perfume="$perfume" />
                    @endforeach
                </div>
            </section>
        @endif
    </div>
</div>
