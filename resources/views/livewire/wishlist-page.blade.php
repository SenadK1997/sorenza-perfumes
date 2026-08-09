<div class="relative overflow-hidden"
     style="background:
        radial-gradient(circle at 15% 10%, rgba(255,220,180,0.35) 0%, transparent 45%),
        radial-gradient(circle at 90% 85%, rgba(255,200,220,0.30) 0%, transparent 45%),
        linear-gradient(180deg, #fdfaf5 0%, #ffffff 60%);">
    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 pt-12 sm:pt-20 pb-24">

        <div class="text-center mb-10 sm:mb-14">
            <div class="flex items-center justify-center gap-2 mb-3">
                <span class="h-px w-8 bg-gradient-to-r from-transparent to-rose-400"></span>
                <span class="text-[10px] uppercase tracking-[0.4em] text-rose-500 font-light">Vaše želje</span>
                <span class="h-px w-8 bg-gradient-to-l from-transparent to-rose-400"></span>
            </div>
            <h1 class="font-serif italic font-light text-4xl sm:text-5xl leading-tight text-gray-900">
                <span class="bg-gradient-to-r from-gray-900 via-rose-500 to-gray-900 bg-clip-text text-transparent">
                    Lista želja
                </span>
            </h1>
            <p class="mt-3 text-sm text-gray-500 tracking-wide italic">
                Vaša selekcija mirisa koje želite isprobati
            </p>
        </div>

        @if(session('wishlist_msg'))
            <div class="mb-6 rounded-xl border border-green-200 bg-green-50/70 p-3 text-center text-sm text-green-800">
                {{ session('wishlist_msg') }}
            </div>
        @endif

        @if($items->isEmpty())
            <div class="rounded-3xl bg-white/70 backdrop-blur border border-white/80 p-10 sm:p-16 text-center shadow-sm max-w-xl mx-auto">
                <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-gradient-to-br from-rose-50 to-amber-50 ring-1 ring-rose-200/70">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-9 w-9 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z"/>
                    </svg>
                </div>
                <h3 class="mt-6 font-serif italic text-2xl text-gray-900">Vaša lista je prazna</h3>
                <p class="mt-2 text-sm text-gray-500">Kliknite srce na bilo kojem parfemu da ga sačuvate ovdje.</p>
                <a href="{{ route('shop') }}"
                   class="mt-6 inline-flex items-center gap-2 rounded-full bg-gradient-to-r from-rose-500 via-pink-500 to-fuchsia-500 px-7 py-3 text-xs font-semibold uppercase tracking-[0.28em] text-white shadow-md hover:opacity-95 transition">
                    Otkrijte kolekciju
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6">
                @foreach($items as $item)
                    @php
                        $genderColor = match($item->gender->value) {
                            'male'   => '#046499',
                            'female' => '#b22eae',
                            'unisex' => '#318218',
                            default  => '#000000',
                        };
                    @endphp
                    <article wire:key="wl-{{ $item->id }}"
                             class="group relative flex flex-col rounded-2xl bg-white/80 backdrop-blur border border-white/90 p-3 shadow-sm hover:shadow-lg transition-shadow">

                        <button wire:click="remove({{ $item->id }})"
                                aria-label="Ukloni sa liste"
                                class="absolute top-4 right-4 z-10 inline-flex h-8 w-8 items-center justify-center rounded-full bg-white/95 shadow-sm ring-1 ring-black/5 hover:scale-110 hover:bg-rose-50 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-rose-500" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 21s-6.716-4.35-9.192-8.36C.72 9.15 2.36 5 6.14 5c2.05 0 3.34 1.13 4.14 2.36C11.08 6.13 12.37 5 14.42 5c3.78 0 5.42 4.15 3.33 7.64C18.716 16.65 12 21 12 21z"/>
                            </svg>
                        </button>

                        <a href="{{ route('products.show', $item->id) }}"
                           class="relative aspect-square overflow-hidden rounded-xl bg-gradient-to-br from-gray-50 to-gray-100">
                            <img src="{{ Storage::url($item->main_image) }}"
                                 alt="{{ $item->name }}" loading="lazy"
                                 class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"/>
                        </a>

                        <a href="{{ route('products.show', $item->id) }}"
                           class="mt-3 text-sm sm:text-base font-bold line-clamp-2 min-h-[2.5rem] leading-tight"
                           style="color: {{ $genderColor }};">
                            {{ $item->inspired_by ?: $item->name }}
                        </a>
                        <p class="mt-1 text-base font-semibold bg-gradient-to-r from-[#8b6914] to-[#BBA14F] bg-clip-text text-transparent">
                            {{ number_format($item->price, 2) }} KM
                        </p>

                        @if($item->is_available)
                            <button wire:click="addToCart({{ $item->id }})"
                                    class="mt-3 inline-flex items-center justify-center gap-2 rounded-full bg-gradient-to-r from-[#8b6914] via-[#BBA14F] to-[#DBC584] px-4 py-2 text-[11px] font-semibold uppercase tracking-widest text-white shadow hover:opacity-95 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                Dodaj u korpu
                            </button>
                        @else
                            <button disabled
                                    class="mt-3 inline-flex items-center justify-center gap-2 rounded-full bg-gray-100 px-4 py-2 text-[11px] font-semibold uppercase tracking-widest text-gray-400 cursor-not-allowed">
                                Uskoro
                            </button>
                        @endif
                    </article>
                @endforeach
            </div>
        @endif
    </div>
</div>
