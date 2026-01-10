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
        {{-- BUTTON LOGIC --}}
        @if($perfume->is_available)
            {{-- ACTIVE BUTTON --}}
            <button
                wire:click="addToCart({{ $perfume->id }})"
                class="w-full cursor-pointer px-5 py-2.5 bg-gradient-to-r from-[#BBA14F] to-[#DBC584] text-white font-bold rounded-full shadow-md transition-all duration-300 text-sm border border-transparent hover:from-white hover:to-white hover:border-[#BBA14F] hover:text-[#BBA14F]"
            >
                Dodaj u korpu
            </button>
        @else
            {{-- DISABLED BUTTON --}}
            <button
                disabled
                class="w-full px-5 py-2.5 bg-gray-100 text-gray-400 font-bold rounded-full text-sm border border-gray-200 cursor-not-allowed"
            >
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