<!-- Modal -->
@if($showModal && $selectedPerfume)
<div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-3xl overflow-auto max-h-[90vh] p-6 relative">
        
        <!-- Close button -->
        <button wire:click="closeQuickPreview" 
                class="absolute right-4 top-4 text-gray-500 hover:text-gray-700 text-lg font-bold">
            ✕
        </button>

        <div x-data="{ mainImage: '{{ Storage::url($selectedPerfume->main_image) }}' }" class="lg:grid lg:grid-cols-2 lg:gap-x-6">
            <!-- IMAGE GALLERY -->
            <div>
                <div class="aspect-square overflow-hidden rounded-2xl bg-white shadow-lg">
                    <img :src="mainImage" alt="{{ $selectedPerfume->name }}" class="h-full w-full object-contain transition-all duration-300">
                </div>
                <div class="mt-4 flex gap-2">
                    <button @click="mainImage='{{ Storage::url($selectedPerfume->main_image) }}'" class="h-16 w-16 rounded-xl border-2 border-gray-300 hover:border-indigo-500 overflow-hidden">
                        <img src="{{ Storage::url($selectedPerfume->main_image) }}" class="h-full w-full object-contain">
                    </button>
                    @if($selectedPerfume->secondary_image)
                        <button @click="mainImage='{{ Storage::url($selectedPerfume->secondary_image) }}'" class="h-16 w-16 rounded-xl border-2 border-gray-300 hover:border-indigo-500 overflow-hidden">
                            <img src="{{ Storage::url($selectedPerfume->secondary_image) }}" class="h-full w-full object-contain">
                        </button>
                    @endif
                </div>
            </div>

            <!-- PERFUME INFO -->
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
                            @endphp
                            <div class="flex items-center gap-2">
                                <div class="h-6 rounded-md text-white text-xs flex items-center justify-center transition-all duration-500"
                                    style="width: {{ $percentage }}%; background-color: {{ $color }};"
                                    title="{{ $name }}">
                                    {{ $name }}
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