<div class="group relative flex flex-col">
    @php
        $accordConfig = config('accords');
        $accordKeys = array_keys($accordConfig);
        $firstAccords = array_slice($perfume->accords ?? [], 0, 2);
    @endphp

    <div class="relative">
        <div class="relative h-72 w-full overflow-hidden rounded-lg bg-gray-100">
            <a href="{{ route('products.show', $perfume->id) }}">
                <img 
                    src="{{ Storage::url($perfume->main_image) }}"
                    alt="{{ $perfume->name }}" 
                    class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105" 
                />
            </a>
            {{-- Ako dodamo slike nota --}}
            {{-- @foreach($firstAccords as $index => $accord)
                @php
                    $name = $accordKeys[$accord['name']] ?? null;
                    
                    if($name) {
                        $lowName = mb_strtolower($name, 'UTF-8');
                        $fileName = str_replace(' ', '-', $lowName) . '.png';
                        $positionClass = ($index === 0) ? 'left-3' : 'right-3';
                    }
                @endphp

                @if($name)
                    <div class="absolute top-3 {{ $positionClass }} z-30 pointer-events-none">
                        <img 
                            src="{{ Storage::url('images/notes/' . $fileName) }}"
                            alt="{{ $name }}"
                            class="w-14 h-14 object-contain drop-shadow-[0_4px_6px_rgba(0,0,0,0.3)]"
                            onerror="this.style.opacity='0';"
                        >
                    </div>
                @endif
            @endforeach --}}

            <div class="absolute inset-x-0 bottom-0 flex items-end justify-end p-3 z-40">
                <div aria-hidden="true"
                     class="pointer-events-none absolute inset-x-0 bottom-0 h-16 bg-gradient-to-t from-black/50 to-transparent">
                </div>
                
                <button
                    wire:click.stop="openQuickPreview({{ $perfume->id }})"
                    class="relative z-50 cursor-pointer rounded-full bg-white/90 px-4 py-2 text-gray-900 hover:bg-[#BBA14F] hover:text-white transition-all flex items-center gap-x-2 text-sm font-medium shadow-sm">
                    <svg stroke="currentColor" fill="none" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" height="1.2em" width="1.2em" xmlns="http://www.w3.org/2000/svg">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                    </svg>
                    <span>Brzi pregled</span>
                </button>
            </div>
        </div>

        <div class="relative mt-4">
            <h3 class="text-sm font-bold text-gray-900">
                Sorénza {{ ucfirst($perfume->tag) }} {{ $perfume->name }} - {{ $perfume->gender->label() }}
            </h3>
            <p class="mt-1 text-sm text-gray-500 italic line-clamp-2 min-h-[2.5rem]">
                Inspirisano od: {{ $perfume->inspired_by }}
            </p>
            <p class="mt-2 text-lg font-semibold text-indigo-600">{{ number_format($perfume->price, 2) }} KM</p>
        </div>
    </div>

    <div class="mt-3">
        <button
            wire:click="addToCart({{ $perfume->id }})"
            class="w-full cursor-pointer px-5 py-2.5 bg-gradient-to-r from-[#BBA14F] to-[#DBC584] text-white font-bold rounded-full shadow-md transition-all duration-300 text-sm border border-transparent hover:from-white hover:to-white hover:border-[#BBA14F] hover:text-[#BBA14F]"
        >
            Dodaj u korpu
        </button>
    </div>
</div>

<style>
    @keyframes float {
        0% { transform: translateY(0px); }
        50% { transform: translateY(-6px); }
        100% { transform: translateY(0px); }
    }
    .animate-float {
        animation: float 4s ease-in-out infinite;
    }
</style>