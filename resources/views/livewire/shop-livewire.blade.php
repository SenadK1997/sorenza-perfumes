<div class="bg-gradient-to-b from-[#fffff] to-[#C9C9C9] min-h-[100vh]">
    <!-- MOBILE FILTERS -->
    <!-- Mobile filter toggle button -->
    <!-- Mobile filter dialog -->
    <div id="mobile-filters" class="fixed inset-0 z-50 hidden lg:hidden">
        <!-- backdrop -->
        <div class="fixed inset-0 bg-black/25" onclick="toggleMobileFilters(false)"></div>
    
        <!-- panel -->
        <div class="fixed right-0 top-0 flex h-full w-[50%] max-w-md flex-col bg-white shadow-xl overflow-y-auto transition-transform transform translate-x-full" id="mobile-filters-panel">
        <div class="flex items-center justify-between p-4 border-b">
            <h2 class="text-lg font-medium text-gray-900">Filteri</h2>
            <button type="button" class="text-gray-400 hover:text-gray-600" onclick="toggleMobileFilters(false)">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
            </button>
        </div>
    
        <!-- Filters -->
        <div class="p-4 space-y-4">
            <!-- Gender -->
            <fieldset>
            <legend class="text-sm font-medium text-gray-900">Spol</legend>
            <div class="space-y-2 mt-2">
                <div class="flex items-center gap-2">
                <input type="checkbox" value="male" wire:model.live="gender" id="gender-male-mobile" class="h-5 w-5 rounded border-gray-300 checked:border-indigo-600 checked:bg-indigo-600">
                <label for="gender-male-mobile" class="text-sm text-gray-500">Muški</label>
                </div>
                <div class="flex items-center gap-2">
                <input type="checkbox" value="female" wire:model.live="gender" id="gender-female-mobile" class="h-5 w-5 rounded border-gray-300 checked:border-indigo-600 checked:bg-indigo-600">
                <label for="gender-female-mobile" class="text-sm text-gray-500">Ženski</label>
                </div>
                <div class="flex items-center gap-2">
                <input type="checkbox" value="unisex" wire:model.live="gender" id="gender-unisex-mobile" class="h-5 w-5 rounded border-gray-300 checked:border-indigo-600 checked:bg-indigo-600">
                <label for="gender-unisex-mobile" class="text-sm text-gray-500">Unisex</label>
                </div>
            </div>
            </fieldset>
    
            <!-- Price -->
            <fieldset>
            <legend class="text-sm font-medium text-gray-900">Cijena</legend>
            <div class="space-y-2 mt-2">
                @foreach([60, 80, 100, 120, 150] as $p)
                <div class="flex items-center gap-2">
                    <input type="checkbox" value="{{ $p }}" wire:model.live="price" id="price-{{ $p }}-mobile" class="h-5 w-5 rounded border-gray-300 checked:border-indigo-600 checked:bg-indigo-600">
                    <label for="price-{{ $p }}-mobile" class="text-sm text-gray-500">{{ $p }}</label>
                </div>
                @endforeach
            </div>
            </fieldset>
        </div>
        </div>
    </div>
    
    <!-- Toggle button -->
    <button class="lg:hidden inline-flex items-center p-2 border rounded" onclick="toggleMobileFilters(true)">
        Filteri
    </button>
  
    <!-- DESKTOP FILTERS + PRODUCTS -->
    <div class="mx-auto max-w-7xl px-4 py-4 lg:grid lg:grid-cols-4 lg:gap-x-8">
        <!-- Desktop filters -->
        <aside class="hidden lg:block space-y-6">
            <!-- Gender -->
            <fieldset>
                <legend class="block text-sm font-medium text-gray-900">Spol</legend>
                <div class="mt-4 space-y-3">
                    @foreach(['male' => 'Muški', 'female' => 'Ženski', 'unisex' => 'Unisex'] as $key => $label)
                        <div class="flex items-center gap-2">
                            <input type="checkbox" value="{{ $key }}" wire:model.live="gender" id="gender-{{ $key }}" class="h-5 w-5 rounded border-gray-300 checked:border-indigo-600 checked:bg-indigo-600">
                            <label for="gender-{{ $key }}" class="text-sm text-gray-500">{{ $label }}</label>
                        </div>
                    @endforeach
                </div>
            </fieldset>

            <!-- Price -->
            <fieldset>
                <legend class="block text-sm font-medium text-gray-900">Cijena</legend>
                <div class="mt-4 space-y-3">
                    @foreach([60, 80, 100, 120, 150] as $p)
                        <div class="flex items-center gap-2">
                            <input type="checkbox" value="{{ $p }}" wire:model.live="price" id="price-{{ $p }}" class="h-5 w-5 rounded border-gray-300 checked:border-indigo-600 checked:bg-indigo-600">
                            <label for="price-{{ $p }}" class="text-sm text-gray-500">{{ $p }}</label>
                        </div>
                    @endforeach
                </div>
            </fieldset>
        </aside>

        <!-- Perfume cards -->
        <div class="lg:col-span-3 flex flex-col">
            <div class="grid grid-cols-1 py-6 sm:grid-cols-2 md:grid-cols-3 gap-6">
                @forelse($perfumes as $perfume)
                    @include('components.perfume-card', ['perfume' => $perfume])
                @empty
                    <p class="col-span-full text-gray-500 text-center py-10">Nema rezultata.</p>
                @endforelse
            </div>

            @if($perfumes->hasPages())
                <div class="mt-12 mb-10 flex justify-center items-center w-full overflow-x-auto">
                    <div class="px-2 w-full max-w-sm sm:max-w-md md:max-w-lg text-center">
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
                class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4"
            >
                <div 
                    @click.outside="$wire.closeQuickPreview()"
                    class="bg-white rounded-xl shadow-2xl w-full max-w-3xl overflow-auto max-h-[90vh] p-6 relative"
                >
                    
                    <button wire:click="closeQuickPreview" 
                            class="absolute right-2 top-2 cursor-pointer text-gray-500 hover:text-gray-800 text-xl font-bold">
                        ✕
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
                            <h1 class="text-2xl sm:text-3xl font-serif font-bold text-gray-900">
                                Sorénza {{ ucfirst($selectedPerfume->tag) }} {{ $selectedPerfume->name }}
                            </h1>
                            <p class="text-gray-600 mt-1">Inspirisano od: {{ $selectedPerfume->inspired_by }}</p>

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

                            <p class="mt-4 text-2xl font-semibold text-indigo-600">{{ number_format($selectedPerfume->price, 2) }} KM</p>

                            <button
                                wire:click="addToCart({{ $selectedPerfume->id }})"
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

                            <p class="mt-2 text-sm text-gray-500">Besplatna dostava za narudžbe iznad 120 KM</p>
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
