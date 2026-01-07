<div class="bg-gradient-to-b from-white to-gray-50">
    <div class="mx-auto max-w-7xl px-4 py-8 lg:px-8">

        <div 
            class="lg:grid lg:grid-cols-2 lg:gap-x-16"
            x-data="{
                mainImage: '{{ asset('storage/' . $perfume->main_image) }}'
            }"
        >

            <!-- IMAGE GALLERY -->
            <div>
                <!-- Main image -->
                <div class="aspect-square overflow-hidden max-h-[500px] rounded-2xl bg-white shadow-lg">
                    <img
                        :src="mainImage"
                        alt="{{ $perfume->name }}"
                        class="h-full w-full object-cover transition-all duration-300"
                    >
                </div>

                <!-- Thumbnails -->
                <div class="mt-6 flex gap-4">
                    <!-- Main thumb -->
                    <button
                        @click="mainImage = '{{ asset('storage/' . $perfume->main_image) }}'"
                        class="h-20 w-20 overflow-hidden rounded-xl border-2 border-gray-300 hover:border-indigo-500"
                    >
                        <img
                            src="{{ asset('storage/' . $perfume->main_image) }}"
                            class="h-full w-full object-cover"
                        >
                    </button>

                    @if($perfume->secondary_image)
                        <button
                            @click="mainImage = '{{ asset('storage/' . $perfume->secondary_image) }}'"
                            class="h-20 w-20 overflow-hidden rounded-xl border-2 border-gray-300 hover:border-indigo-500"
                        >
                            <img
                                src="{{ asset('storage/' . $perfume->secondary_image) }}"
                                class="h-full w-full object-cover"
                            >
                        </button>
                    @endif
                </div>
            </div>

            <!-- PERFUME INFO -->
            <div class="mt-12 lg:mt-0">
                <h1 class="text-4xl font-serif font-bold tracking-tight text-gray-900">
                    Sorénza {{ ucfirst($perfume->tag) }} {{ $perfume->name }}
                </h1>
                <h2>Inspisano od: {{ $perfume->inspired_by }}</h2>

                @if($perfume->description)
                    <p class="mt-6 text-lg leading-relaxed text-gray-600">
                        {{ $perfume->description }}
                    </p>
                @endif
                @php
                    $accordConfig = config('accords');
                    $accordKeys = array_keys($accordConfig);
                    $accords = $perfume->accords ?? [];
                @endphp

                @if(count($accords) > 0)
                    <h2 class="mt-6 mb-2 text-lg font-semibold text-gray-900">Note:</h2>

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

                <p class="mt-4 text-3xl font-semibold text-indigo-600">
                    {{ number_format($perfume->price, 2) }} KM
                </p>

                <!-- Divider -->
                <div class="my-8 h-px w-full bg-gray-200"></div>

                <!-- CTA -->
                <button
                    wire:click="addToCart({{ $perfume->id }})"
                    class="
                        cursor-pointer px-5 py-2.5
                        bg-gradient-to-r from-[#BBA14F] to-[#DBC584]
                        text-white font-medium rounded-full shadow-md
                        transition-all duration-300 text-sm

                        hover:bg-none
                        hover:border-1
                        hover:border-black
                        hover:bg-white
                        hover:text-[#BBA14F]
                        {{-- hover:shadow-lg --}}
                        {{-- hover:scale-105 --}}
                    "
                >
                    Dodaj u korpu
                    <span class="sr-only">, {{ $perfume->name }}</span>
                </button>

                <p class="mt-4 text-sm text-gray-500 text-left">
                    Besplatna dostava za narudžbe iznad 120 KM
                </p>
            </div>

        </div>
    </div>
</div>
