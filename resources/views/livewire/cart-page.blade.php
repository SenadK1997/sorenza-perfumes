<div class="relative overflow-hidden"
     style="background:
        radial-gradient(circle at 15% 10%, rgba(255,220,180,0.35) 0%, transparent 45%),
        radial-gradient(circle at 90% 85%, rgba(255,200,220,0.30) 0%, transparent 45%),
        linear-gradient(180deg, #fdfaf5 0%, #ffffff 60%);">

    {{-- Decorative glow strip --}}
    <div class="pointer-events-none absolute inset-x-0 top-0 h-40 bg-gradient-to-b from-white/60 to-transparent"></div>

    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 pt-12 sm:pt-20 pb-24">

        {{-- Editorial heading --}}
        <div class="text-center mb-10 sm:mb-14">
            <div class="flex items-center justify-center gap-2 mb-3">
                <span class="h-px w-8 bg-gradient-to-r from-transparent to-amber-500"></span>
                <span class="text-[10px] uppercase tracking-[0.4em] text-amber-700 font-light">Vaša selekcija</span>
                <span class="h-px w-8 bg-gradient-to-l from-transparent to-amber-500"></span>
            </div>
            <h1 class="font-serif italic font-light text-4xl sm:text-5xl leading-tight text-gray-900">
                <span class="bg-gradient-to-r from-gray-900 via-amber-800 to-gray-900 bg-clip-text text-transparent">
                    Vaša Korpa
                </span>
            </h1>
            <p class="mt-3 text-sm text-gray-500 tracking-wide italic">
                Pregledajte mirise koje ste izabrali prije naplate
            </p>
        </div>

        <div class="grid gap-8 lg:grid-cols-12 lg:items-start lg:gap-x-10 xl:gap-x-14">

            {{-- LEFT: cart items --}}
            <section aria-labelledby="cart-heading" class="lg:col-span-7 space-y-4">
                <h2 id="cart-heading" class="sr-only">Artikli u vašoj korpi</h2>

                @forelse($items as $item)
                    <article wire:key="cart-item-{{ $item->id }}"
                             class="group relative flex gap-4 sm:gap-6 rounded-2xl bg-white/70 backdrop-blur border border-white/90 p-4 sm:p-5 shadow-sm hover:shadow-lg transition-shadow">

                        {{-- Image --}}
                        <a href="{{ route('products.show', $item->id) }}"
                           class="relative shrink-0 rounded-xl overflow-hidden bg-gradient-to-br from-gray-50 to-gray-100 ring-1 ring-black/5">
                            <img src="{{ Storage::url($item->main_image) }}"
                                 alt="{{ $item->name }}"
                                 loading="lazy"
                                 class="h-24 w-24 sm:h-32 sm:w-32 object-cover transition-transform duration-500 group-hover:scale-105" />
                        </a>

                        {{-- Info --}}
                        <div class="flex-1 min-w-0 flex flex-col justify-between">
                            <div>
                                <div class="flex items-start justify-between gap-3">
                                    <div class="min-w-0">
                                        <a href="{{ route('products.show', $item->id) }}"
                                           class="block text-base sm:text-lg font-semibold text-gray-900 truncate hover:text-amber-800 transition-colors">
                                            {{ $item->name }}
                                        </a>
                                        @if($item->inspired_by)
                                            <p class="mt-0.5 text-xs sm:text-sm italic text-gray-500 truncate">
                                                Inspirisano od: {{ $item->inspired_by }}
                                            </p>
                                        @endif
                                    </div>

                                    <button wire:click="removeItem({{ $item->id }})"
                                            type="button"
                                            aria-label="Ukloni proizvod"
                                            class="shrink-0 -mt-1 -mr-1 inline-flex h-8 w-8 items-center justify-center rounded-full text-gray-400 hover:text-red-600 hover:bg-red-50 transition">
                                        <svg viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4">
                                            <path d="M6.28 5.22a.75.75 0 0 0-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 1 0 1.06 1.06L10 11.06l3.72 3.72a.75.75 0 1 0 1.06-1.06L11.06 10l3.72-3.72a.75.75 0 0 0-1.06-1.06L10 8.94 6.28 5.22Z" />
                                        </svg>
                                    </button>
                                </div>

                                <p class="mt-2 inline-flex items-center gap-1.5 text-xs text-green-700">
                                    <svg viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4 text-green-600">
                                        <path d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z" clip-rule="evenodd" fill-rule="evenodd" />
                                    </svg>
                                    Na stanju
                                </p>
                            </div>

                            {{-- Quantity + price --}}
                            <div class="mt-4 flex items-center justify-between gap-3">
                                <div class="inline-flex items-center rounded-full border border-amber-200/80 bg-white/80 backdrop-blur px-1 py-1">
                                    <button
                                        wire:click="updateQuantity({{ $item->id }}, {{ max(1, $item->quantity - 1) }})"
                                        @if($item->quantity <= 1) disabled @endif
                                        class="h-8 w-8 inline-flex items-center justify-center rounded-full text-amber-800 hover:bg-amber-100 disabled:opacity-40 disabled:hover:bg-transparent transition"
                                        aria-label="Smanji količinu">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4"/>
                                        </svg>
                                    </button>
                                    <span class="min-w-[2rem] text-center text-sm font-semibold text-gray-900 tabular-nums">
                                        {{ $item->quantity }}
                                    </span>
                                    <button
                                        wire:click="updateQuantity({{ $item->id }}, {{ min(10, $item->quantity + 1) }})"
                                        @if($item->quantity >= 10) disabled @endif
                                        class="h-8 w-8 inline-flex items-center justify-center rounded-full text-amber-800 hover:bg-amber-100 disabled:opacity-40 disabled:hover:bg-transparent transition"
                                        aria-label="Povećaj količinu">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                                        </svg>
                                    </button>
                                </div>

                                <div class="text-right">
                                    <div class="text-[10px] uppercase tracking-[0.25em] text-gray-400">Ukupno</div>
                                    <div class="text-base sm:text-lg font-semibold bg-gradient-to-r from-[#8b6914] to-[#BBA14F] bg-clip-text text-transparent tabular-nums">
                                        {{ number_format($item->price * $item->quantity, 2) }} KM
                                    </div>
                                    <div class="text-[11px] text-gray-500">
                                        {{ number_format($item->price, 2) }} KM × {{ $item->quantity }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="rounded-3xl bg-white/70 backdrop-blur border border-white/80 p-10 sm:p-16 text-center shadow-sm">
                        <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-gradient-to-br from-amber-50 to-rose-50 ring-1 ring-amber-200/70">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-9 w-9 text-amber-800" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"/>
                            </svg>
                        </div>
                        <h3 class="mt-6 font-serif italic text-2xl text-gray-900">Vaša korpa je prazna</h3>
                        <p class="mt-2 text-sm text-gray-500">Otkrijte našu kolekciju luksuznih mirisa i pronađite svoj potpis.</p>
                        <a href="{{ route('shop') }}"
                           class="mt-6 inline-flex items-center gap-2 rounded-full bg-gradient-to-r from-[#8b6914] via-[#BBA14F] to-[#DBC584] px-7 py-3 text-xs font-semibold uppercase tracking-[0.28em] text-white shadow-md hover:opacity-95 transition">
                            Otkrijte kolekciju
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                            </svg>
                        </a>
                    </div>
                @endforelse

                {{-- Trust badges strip --}}
                @if(!$items->isEmpty())
                    <div class="mt-8 grid grid-cols-2 sm:grid-cols-4 gap-3">
                        <div class="flex items-start gap-3 rounded-xl bg-white/60 backdrop-blur border border-white/80 p-3 shadow-sm">
                            <div class="mt-0.5 shrink-0 h-8 w-8 rounded-full bg-gradient-to-br from-amber-50 to-rose-50 ring-1 ring-amber-200 flex items-center justify-center text-amber-800">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16V6a1 1 0 00-1-1H3v11h2m8 0H9m10 0h2v-6l-3-4h-5v4h6"/></svg>
                            </div>
                            <div>
                                @if($alwaysFree)
                                    <div class="text-[11px] font-semibold text-gray-900">Besplatna dostava</div>
                                @elseif($freeShippingThreshold > 0)
                                    <div class="text-[11px] font-semibold text-gray-900">Besplatna dostava</div>
                                    <div class="text-[11px] text-gray-500">iznad {{ number_format($freeShippingThreshold, 0) }} KM</div>
                                @else
                                    <div class="text-[11px] font-semibold text-gray-900">Dostava</div>
                                    <div class="text-[11px] text-gray-500">{{ number_format(\App\Services\ShippingCalculator::flatFee(), 2) }} KM</div>
                                @endif
                            </div>
                        </div>
                        <div class="flex items-start gap-3 rounded-xl bg-white/60 backdrop-blur border border-white/80 p-3 shadow-sm">
                            <div class="mt-0.5 shrink-0 h-8 w-8 rounded-full bg-gradient-to-br from-amber-50 to-rose-50 ring-1 ring-amber-200 flex items-center justify-center text-amber-800">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h13a5 5 0 010 10h-1M3 10l4-4M3 10l4 4"/></svg>
                            </div>
                            <div>
                                <div class="text-[11px] font-semibold text-gray-900">Besplatni povrat</div>
                                <div class="text-[11px] text-gray-500">do {{ $refundDays }} dana</div>
                            </div>
                        </div>
                        <div class="flex items-start gap-3 rounded-xl bg-white/60 backdrop-blur border border-white/80 p-3 shadow-sm">
                            <div class="mt-0.5 shrink-0 h-8 w-8 rounded-full bg-gradient-to-br from-amber-50 to-rose-50 ring-1 ring-amber-200 flex items-center justify-center text-amber-800">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/></svg>
                            </div>
                            <div>
                                <div class="text-[11px] font-semibold text-gray-900">Plaćanje pouzećem</div>
                            </div>
                        </div>
                        <div class="flex items-start gap-3 rounded-xl bg-white/60 backdrop-blur border border-white/80 p-3 shadow-sm">
                            <div class="mt-0.5 shrink-0 h-8 w-8 rounded-full bg-gradient-to-br from-amber-50 to-rose-50 ring-1 ring-amber-200 flex items-center justify-center text-amber-800">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 2M12 2a10 10 0 100 20 10 10 0 000-20z"/></svg>
                            </div>
                            <div>
                                <div class="text-[11px] font-semibold text-gray-900">Isporuka 1–3 dana</div>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Continue shopping --}}
                @if(!$items->isEmpty())
                    <div class="mt-4">
                        <a href="{{ route('shop') }}"
                           class="inline-flex items-center gap-2 text-xs uppercase tracking-[0.28em] font-medium text-amber-900 hover:text-amber-700 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                            </svg>
                            Nastavi kupovinu
                        </a>
                    </div>
                @endif
            </section>

            {{-- RIGHT: summary --}}
            <aside aria-labelledby="summary-heading" class="lg:col-span-5 lg:sticky lg:top-24">
                <div class="relative rounded-3xl overflow-hidden bg-white/70 backdrop-blur border border-white/90 shadow-xl">
                    {{-- top ribbon --}}
                    <div class="h-1.5 w-full bg-gradient-to-r from-[#8b6914] via-[#BBA14F] to-[#DBC584]"></div>

                    <div class="p-6 sm:p-8">
                        <div class="flex items-center gap-2 mb-6">
                            <span class="h-px w-6 bg-amber-500"></span>
                            <h2 id="summary-heading" class="text-[10px] uppercase tracking-[0.4em] text-amber-700 font-light">
                                Sažetak narudžbe
                            </h2>
                        </div>

                        {{-- Free shipping progress / celebration --}}
                        @if(! $alwaysFree && $freeShippingThreshold > 0 && $subtotal > 0 && $amountToFree > 0)
                            <div class="mb-6 rounded-2xl border border-amber-200/70 bg-gradient-to-br from-amber-50/80 to-white p-4">
                                <div class="flex items-center gap-2 text-sm text-amber-900">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16V6a1 1 0 00-1-1H3v11h2m8 0H9m10 0h2v-6l-3-4h-5v4h6"/>
                                    </svg>
                                    <span>Dodajte još <strong class="tabular-nums">{{ number_format($amountToFree, 2) }} KM</strong> za <strong>besplatnu dostavu</strong></span>
                                </div>
                                <div class="mt-3 h-1.5 w-full bg-amber-100 rounded-full overflow-hidden">
                                    <div class="h-full bg-gradient-to-r from-[#8b6914] via-[#BBA14F] to-[#DBC584] transition-all duration-500"
                                         style="width: {{ min(100, ($subtotal / max(1,$freeShippingThreshold)) * 100) }}%"></div>
                                </div>
                            </div>
                        @elseif($qualifiesForFree && $subtotal > 0)
                            <div class="mb-6 rounded-2xl border border-green-200/80 bg-green-50/70 p-4">
                                <p class="text-sm text-green-800 font-medium flex items-center gap-2">
                                    <svg class="h-5 w-5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                    @if($alwaysFree) Besplatna dostava na svaku narudžbu! @else Čestitamo — ostvarili ste besplatnu dostavu! @endif
                                </p>
                            </div>
                        @endif

                        {{-- Coupon --}}
                        <div class="mb-6">
                            <label for="coupon" class="block text-[10px] uppercase tracking-[0.3em] text-gray-500 font-medium mb-2">
                                Imate kupon?
                            </label>

                            @if(session()->has('coupon'))
                                <div class="flex items-center justify-between bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 px-4 py-3 rounded-xl">
                                    <div class="flex items-center gap-2 text-sm text-green-800">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M12 3l8 4v6c0 5-3.5 8-8 9-4.5-1-8-4-8-9V7l8-4z"/>
                                        </svg>
                                        <span class="font-semibold tracking-wide">{{ session('coupon')['code'] }}</span>
                                    </div>
                                    <button wire:click="removeCoupon" class="text-[10px] uppercase tracking-widest text-red-600 hover:text-red-800 font-medium">
                                        Ukloni
                                    </button>
                                </div>
                            @else
                                <div class="flex gap-2">
                                    <input id="coupon" type="text" wire:model="couponCode"
                                           placeholder="Unesite kod"
                                           class="block w-full rounded-full border-amber-200/80 bg-white/80 backdrop-blur px-4 py-2.5 text-sm text-gray-900 placeholder:text-gray-400 shadow-sm focus:border-amber-500 focus:ring-amber-500">
                                    <button wire:click="applyCoupon"
                                            class="shrink-0 rounded-full border border-amber-800/60 bg-white px-5 py-2.5 text-xs font-medium uppercase tracking-[0.2em] text-amber-900 hover:bg-gradient-to-r hover:from-[#BBA14F] hover:to-[#DBC584] hover:text-white hover:border-transparent transition-all">
                                        Primijeni
                                    </button>
                                </div>
                                @error('couponCode') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                            @endif
                        </div>

                        {{-- Totals --}}
                        <dl class="space-y-3 border-t border-amber-100/80 pt-5">
                            <div class="flex items-center justify-between text-sm">
                                <dt class="text-gray-600">Cijena artikala</dt>
                                <dd class="font-medium text-gray-900 tabular-nums">{{ number_format($subtotal, 2) }} KM</dd>
                            </div>

                            @if($discount > 0)
                                <div class="flex items-center justify-between text-sm text-green-700">
                                    <dt>Popust</dt>
                                    <dd class="font-medium tabular-nums">− {{ number_format($discount, 2) }} KM</dd>
                                </div>
                            @endif

                            <div class="flex items-center justify-between text-sm">
                                <dt class="text-gray-600 flex items-center gap-1.5">
                                    Dostava
                                    @if($qualifiesForFree)
                                        <span class="inline-flex items-center rounded-full bg-green-100 px-2 py-0.5 text-[10px] font-semibold text-green-700 uppercase tracking-wider">Free</span>
                                    @endif
                                </dt>
                                <dd class="font-medium text-gray-900 tabular-nums">
                                    @if($subtotal == 0)
                                        0.00 KM
                                    @elseif($shipping == 0)
                                        <span class="text-green-700">Besplatno</span>
                                    @else
                                        {{ number_format($shipping, 2) }} KM
                                    @endif
                                </dd>
                            </div>

                            <div class="flex items-baseline justify-between border-t border-amber-100/80 pt-4">
                                <dt class="text-sm uppercase tracking-[0.25em] text-gray-700 font-medium">Ukupno</dt>
                                <dd class="font-serif text-2xl bg-gradient-to-r from-[#8b6914] via-[#BBA14F] to-[#8b6914] bg-clip-text text-transparent tabular-nums">
                                    {{ number_format($total, 2) }} KM
                                </dd>
                            </div>
                        </dl>

                        {{-- CTA --}}
                        <div class="mt-6">
                            @php $isEmpty = $items->isEmpty(); @endphp

                            <a href="{{ $isEmpty ? '#' : route('checkout') }}"
                               @if($isEmpty) onclick="return false;" @endif
                               class="group flex items-center justify-center gap-3 w-full rounded-full px-6 py-4 text-xs font-semibold uppercase tracking-[0.28em] shadow-lg transition-all
                               {{ $isEmpty
                                    ? 'bg-gray-300 text-gray-500 cursor-not-allowed pointer-events-none'
                                    : 'bg-gradient-to-r from-[#8b6914] via-[#BBA14F] to-[#DBC584] text-white hover:opacity-95 hover:shadow-xl active:scale-[0.98]'
                               }}">
                                @if($isEmpty)
                                    Korpa je prazna
                                @else
                                    Nastavi na naplatu
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transition-transform group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                    </svg>
                                @endif
                            </a>
                        </div>

                        {{-- Refund reminder --}}
                        <div class="mt-5 flex items-center justify-center gap-2 text-[11px] text-gray-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-amber-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h13a5 5 0 010 10h-1M3 10l4-4M3 10l4 4"/>
                            </svg>
                            Besplatni povrat u roku od <strong class="text-gray-700">{{ $refundDays }} dana</strong>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</div>
