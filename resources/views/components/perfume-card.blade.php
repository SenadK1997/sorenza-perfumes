<div class="group relative flex flex-col rounded-2xl bg-white/70 backdrop-blur-sm border border-white/90 p-2 sm:p-3 shadow-[0_8px_30px_-12px_rgba(190,80,150,0.20)] hover:shadow-[0_20px_50px_-15px_rgba(160,60,180,0.45)] hover:-translate-y-1 hover:border-fuchsia-100 transition-all duration-300">
    @php
        $accordConfig = config('accords');
        $accordKeys = array_keys($accordConfig);
        $firstAccords = array_slice($perfume->accords ?? [], 0, 2);
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
    @endphp

    <div class="relative">
        <div class="relative h-52 sm:h-64 md:h-72 w-full overflow-hidden rounded-xl bg-gradient-to-br from-gray-50 to-gray-100 shadow-sm group-hover:shadow-lg transition-shadow duration-500">
            @if($genderLabel)
                <span class="absolute left-2 top-2 sm:left-3 sm:top-3 z-30 inline-flex items-center justify-center rounded-full px-2.5 sm:px-3 py-1 text-[10px] sm:text-[11px] font-bold uppercase tracking-widest text-white shadow-md ring-1 ring-white/40"
                      style="background-color: {{ $genderColor }};">
                    {{ $genderLabel }}
                </span>
            @endif

            {{-- Sale badge (top right) --}}
            @if($perfume->is_on_sale)
                <span class="absolute right-2 top-2 sm:right-3 sm:top-3 z-30 inline-flex items-center rounded-full bg-gradient-to-r from-rose-500 to-red-600 px-2 py-0.5 text-[9px] sm:text-[10px] font-bold uppercase tracking-wider text-white shadow ring-1 ring-white/40">
                    Akcija −{{ (int) $perfume->discount_percentage }}%
                </span>
            @endif

            {{-- Wishlist heart (bottom left) --}}
            <div class="absolute left-2 bottom-2 sm:left-3 sm:bottom-3 z-30" wire:ignore>
                <livewire:wishlist-button :perfume-id="$perfume->id" size="sm" :key="'wl-'.$perfume->id" />
            </div>
            <a href="{{ route('products.show', $perfume->id) }}">
                <img
                    src="{{ Storage::url($perfume->main_image) }}"
                    alt="{{ $perfume->name }}"
                    loading="lazy"
                    decoding="async"
                    class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105 {{ !$perfume->is_available ? 'grayscale opacity-70' : '' }}"
                />
            </a>
            {{-- RED TRACK --}}
            @if(!$perfume->is_available)
                <div class="absolute inset-0 z-20 flex items-center justify-center pointer-events-none">
                    <div class="bg-red-600/90 animate-pulse-red text-white py-2 w-full text-center -rotate-12 shadow-lg border-y-2 border-white/30 flex flex-col items-center">
                        <span class="text-[10px] uppercase tracking-widest font-bold">Uskoro</span>
                        
                        @if($perfume->restock_date)
                            {{-- Digital Countdown Timer --}}
                            <div class="text-[11px] font-mono font-bold tracking-tight" 
                                x-data="fullCountdown('{{ $perfume->restock_date->format('Y-m-d H:i:s') }}')" 
                                x-init="initTimer()">
                                <span x-text="timeDisplay">Učitavanje...</span>
                            </div>
                        @else
                            <span class="text-xs font-medium">Dostupno uskoro</span>
                        @endif
                    </div>
                </div>
            @endif
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
                    aria-label="Brzi pregled"
                    class="relative z-50 cursor-pointer rounded-full bg-white/95 backdrop-blur px-2.5 py-1.5 sm:px-4 sm:py-2 text-gray-800 hover:bg-gradient-to-r hover:from-violet-500 hover:via-fuchsia-500 hover:to-rose-500 hover:text-white transition-all flex items-center gap-x-1 sm:gap-x-2 text-[11px] sm:text-sm font-medium shadow-lg ring-1 ring-white/80 hover:ring-white/40 hover:scale-105">
                    <svg stroke="currentColor" fill="none" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                    </svg>
                    <span class="hidden sm:inline">Brzi pregled</span>
                    <span class="sm:hidden">Pregled</span>
                </button>
            </div>
        </div>


        {{-- // zelena za unisex: #318218
        // muska : #046499
        // zenska : #b22eae --}}
        <div class="relative mt-4">
            @php
                $parts = explode(' - ', $perfume->inspired_by);
            @endphp
           <h3 class="text-sm sm:text-lg font-bold min-h-[2.5rem] sm:min-h-[3rem] line-clamp-2 group-hover:opacity-90 transition-opacity leading-tight" style="color: {{ $genderColor }};">
                {{ count($parts) === 2 ? "$parts[1] - $parts[0]" : $perfume->inspired_by }} ({{ $perfume->name }})
            </h3>
            @if($perfume->is_on_sale)
                <div class="mt-1.5 sm:mt-2 flex items-baseline gap-2 flex-wrap">
                    <p class="text-base sm:text-lg font-semibold bg-gradient-to-r from-rose-600 via-red-500 to-orange-500 bg-clip-text text-transparent">{{ number_format($perfume->price, 2) }} KM</p>
                    <p class="text-xs sm:text-sm font-medium text-gray-400 line-through">{{ number_format($perfume->original_price, 2) }} KM</p>
                </div>
            @else
                <p class="mt-1.5 sm:mt-2 text-base sm:text-lg font-semibold bg-gradient-to-r from-violet-600 via-fuchsia-500 to-rose-500 bg-clip-text text-transparent">{{ number_format($perfume->price, 2) }} KM</p>
            @endif
        </div>
    </div>

    <div class="mt-3">
        {{-- BUTTON LOGIC --}}
        @if($perfume->is_available)
            {{-- ACTIVE BUTTON --}}
            <button
                wire:click="addToCart({{ $perfume->id }})"
                class="cart-btn group/btn relative w-full overflow-hidden cursor-pointer px-3 sm:px-5 py-2.5 sm:py-3 bg-gradient-to-r from-violet-600 via-fuchsia-500 to-rose-500 text-white font-bold uppercase tracking-[0.15em] rounded-full shadow-[0_8px_20px_-6px_rgba(190,80,180,0.6)] hover:shadow-[0_14px_35px_-8px_rgba(220,60,140,0.75)] hover:-translate-y-0.5 hover:from-violet-500 hover:via-fuchsia-400 hover:to-rose-400 active:translate-y-0 transition-all duration-300 text-[10px] sm:text-xs border border-white/30"
            >
                <span class="relative z-10 inline-flex items-center justify-center gap-1.5 sm:gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 sm:h-4 sm:w-4 transition-transform duration-300 group-hover/btn:rotate-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <span>Dodaj u korpu</span>
                </span>
                {{-- Shimmer sweep --}}
                <span aria-hidden="true" class="pointer-events-none absolute inset-0 -translate-x-full bg-[linear-gradient(120deg,transparent_30%,rgba(255,255,255,0.5)_50%,transparent_70%)] transition-transform duration-700 ease-out group-hover/btn:translate-x-full"></span>
            </button>
        @else
            {{-- DISABLED BUTTON --}}
            <button
                disabled
                class="w-full px-3 sm:px-5 py-2.5 sm:py-3 bg-gray-100/80 text-gray-400 font-bold uppercase tracking-[0.15em] rounded-full text-[10px] sm:text-xs border border-gray-200 cursor-not-allowed inline-flex items-center justify-center gap-1.5 sm:gap-2"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 sm:h-4 sm:w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 2M12 2a10 10 0 100 20 10 10 0 000-20z"/>
                </svg>
                Uskoro dostupno
            </button>
        @endif
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
    @keyframes pulse-red {
    0% { box-shadow: 0 0 0 0 rgba(220, 38, 38, 0.4); }
    70% { box-shadow: 0 0 0 10px rgba(220, 38, 38, 0); }
    100% { box-shadow: 0 0 0 0 rgba(220, 38, 38, 0); }
    }
    .animate-pulse-red {
        animation: pulse-red 2s infinite;
}
</style>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('fullCountdown', (expireTime) => ({
            timeDisplay: '',
            initTimer() {
                const target = new Date(expireTime).getTime();
                
                const update = () => {
                    const now = new Date().getTime();
                    const diff = target - now;
    
                    if (diff <= 0) {
                        this.timeDisplay = "DOSTUPNO!";
                        return;
                    }
    
                    const days = Math.floor(diff / (1000 * 60 * 60 * 24));
                    const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    const mins = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                    const secs = Math.floor((diff % (1000 * 60)) / 1000);
    
                    let dayLabel = "";
                    if (days > 0) {
                        // Check grammar for "Dan" vs "Dana" and capitalize
                        if (days % 10 === 1 && days % 100 !== 11) {
                            dayLabel = `${days} Dan `; // Removed comma
                        } else {
                            dayLabel = `${days} Dana `; // Removed comma
                        }
                    }
                    
                    const clock = [hours, mins, secs]
                        .map(v => v < 10 ? "0" + v : v)
                        .join(":");
    
                    this.timeDisplay = dayLabel + clock;
                };
    
                update();
                setInterval(update, 1000);
            }
        }));
    });
    </script>