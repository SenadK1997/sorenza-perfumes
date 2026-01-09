<div class="bg-white">
    <div class="mx-auto max-w-2xl px-4 pt-16 pb-24 sm:px-6 lg:max-w-7xl lg:px-8">
        <h1 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">Vaša korpa</h1>
        
        <div class="mt-12 lg:grid lg:grid-cols-12 lg:items-start lg:gap-x-12 xl:gap-x-16">
            <section aria-labelledby="cart-heading" class="lg:col-span-7">
                <h2 id="cart-heading" class="sr-only">Artikli u vašoj korpi</h2>

                <ul role="list" class="divide-y divide-gray-200 border-t border-b border-gray-200">
                    @forelse($items as $item)
                        <li class="flex py-6 sm:py-10" wire:key="cart-item-{{ $item->id }}">
                            <div class="shrink-0">
                                <img src="{{ Storage::url($item->main_image) }}" alt="{{ $item->name }}" class="size-24 rounded-md object-cover sm:size-48" />
                            </div>

                            <div class="ml-4 flex flex-1 flex-col justify-between sm:ml-6">
                                <div class="relative pr-9 sm:grid sm:grid-cols-2 sm:gap-x-6 sm:pr-0">
                                    <div>
                                        <div class="flex justify-between">
                                            <h3 class="text-sm font-medium text-gray-700 hover:text-gray-800">
                                                {{ $item->name }}
                                            </h3>
                                        </div>
                                        <p class="mt-1 text-sm text-gray-500 italic">Inspirisano od: {{ $item->inspired_by }}</p>
                                        <p class="mt-1 text-sm font-medium text-gray-900">{{ number_format($item->price, 2) }} KM</p>
                                    </div>

                                    <div class="mt-4 sm:mt-0 sm:pr-9">
                                        <div class="grid w-full max-w-16 grid-cols-1">
                                            <select 
                                                wire:change="updateQuantity({{ $item->id }}, $event.target.value)"
                                                class="col-start-1 row-start-1 appearance-none rounded-md bg-white py-1.5 pr-8 pl-3 text-base text-gray-900 outline-1 outline-gray-300 focus:outline-indigo-600 sm:text-sm/6"
                                            >
                                                @for($i = 1; $i <= 10; $i++)
                                                    <option value="{{ $i }}" {{ $item->quantity == $i ? 'selected' : '' }}>{{ $i }}</option>
                                                @endfor
                                            </select>
                                        </div>

                                        <div class="absolute top-0 right-0">
                                            <button wire:click="removeItem({{ $item->id }})" type="button" class="-m-2 inline-flex p-2 text-gray-400 hover:text-red-500 transition-colors">
                                                <span class="sr-only">Ukloni</span>
                                                <svg viewBox="0 0 20 20" fill="currentColor" class="size-5">
                                                    <path d="M6.28 5.22a.75.75 0 0 0-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 1 0 1.06 1.06L10 11.06l3.72 3.72a.75.75 0 1 0 1.06-1.06L11.06 10l3.72-3.72a.75.75 0 0 0-1.06-1.06L10 8.94 6.28 5.22Z" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <p class="mt-4 flex space-x-2 text-sm text-gray-700">
                                    <svg viewBox="0 0 20 20" fill="currentColor" class="size-5 shrink-0 text-green-500">
                                        <path d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z" clip-rule="evenodd" fill-rule="evenodd" />
                                    </svg>
                                    <span>Na stanju</span>
                                </p>
                            </div>
                        </li>
                    @empty
                        <li class="py-12 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                            <p class="mt-4 text-lg font-medium text-gray-900">Vaša korpa je prazna.</p>
                            <a href="/shop" class="mt-2 inline-block text-indigo-600 font-medium hover:text-indigo-500">Nastavi kupovinu &rarr;</a>
                        </li>
                    @endforelse
                </ul>
            </section>

            <section aria-labelledby="summary-heading" class="mt-16 rounded-lg bg-gray-50 px-4 py-6 sm:p-6 lg:col-span-5 lg:mt-0 lg:p-8">
                <h2 id="summary-heading" class="text-lg font-medium text-gray-900">Sažetak narudžbe</h2>
                @if($subtotal > 0 && $subtotal < 120)
                    <div class="mt-4 p-4 bg-indigo-50 rounded-xl border border-indigo-100 mb-6">
                        <p class="text-sm text-indigo-700">
                            Dodajte još <span class="font-bold">{{ number_format(120 - $subtotal, 2) }} KM</span> za <strong>besplatnu dostavu</strong>!
                        </p>
                        <div class="mt-2 h-1.5 w-full bg-indigo-200 rounded-full overflow-hidden">
                            <div class="h-full bg-indigo-600 transition-all duration-500" style="width: {{ ($subtotal / 120) * 100 }}%"></div>
                        </div>
                    </div>
                @elseif($subtotal >= 120)
                    <div class="mt-4 p-4 bg-green-50 rounded-xl border border-green-100 mb-6">
                        <p class="text-sm text-green-700 font-medium flex items-center gap-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            Čestitamo! Ostvarili ste besplatnu dostavu.
                        </p>
                    </div>
                @endif
                <div class="mt-6 mb-6">
                    <label for="coupon" class="text-sm font-medium text-gray-700">Imate kupon?</label>
                    
                    @if(session()->has('coupon'))
                        <div class="mt-2 flex items-center justify-between bg-green-50 border border-green-200 px-4 py-2 rounded-lg">
                            <span class="text-sm text-green-700 font-medium italic">Kupon: {{ session('coupon')['code'] }}</span>
                            <button wire:click="removeCoupon" class="text-xs text-red-500 hover:underline">Ukloni</button>
                        </div>
                    @else
                        <div class="mt-2 flex gap-2">
                            <input type="text" wire:model="couponCode" placeholder="Unesite kod" 
                                class="block w-full px-2 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <button wire:click="applyCoupon" 
                                class="cursor-pointer rounded-md bg-gray-200 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-300 transition">
                                Primijeni
                            </button>
                        </div>
                        @error('couponCode') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    @endif
                </div>
                <dl class="space-y-4">
                    <div class="flex items-center justify-between">
                        <dt class="text-sm text-gray-600">Cijena artikala</dt>
                        <dd class="text-sm font-medium text-gray-900">{{ number_format($subtotal, 2) }} KM</dd>
                    </div>

                    @if($discount > 0)
                        <div class="flex items-center justify-between text-green-600">
                            <dt class="text-sm">Popust</dt>
                            <dd class="text-sm font-medium">- {{ number_format($discount, 2) }} KM</dd>
                        </div>
                    @endif

                    <div class="flex items-center justify-between border-t border-gray-200 pt-4">
                        <dt class="flex items-center text-sm text-gray-600">
                            <span>Dostava</span>
                        </dt>
                        <dd class="text-sm font-medium text-gray-900">
                            {{ ($shipping == 0 && $subtotal > 0) || $subtotal >= 120 ? 'Besplatno' : ($subtotal == 0 ? '0.00 KM' : number_format($shipping, 2) . ' KM') }}
                        </dd>
                    </div>
                    <div class="flex items-center justify-between border-t border-gray-200 pt-4">
                        <dt class="text-base font-medium text-gray-900">Ukupno za platiti</dt>
                        <dd class="text-base font-medium text-gray-900">{{ number_format($total, 2) }} KM</dd>
                    </div>
                </dl>

                <div class="mt-6">
                    @php $isEmpty = $items->isEmpty(); @endphp
                    
                    <a href="{{ $isEmpty ? '#' : route('checkout') }}" 
                       @if($isEmpty) onclick="return false;" @endif
                       class="flex items-center justify-center w-full rounded-md border border-transparent px-4 py-3 text-base font-medium text-white shadow-sm transition-all duration-200
                       {{ $isEmpty 
                            ? 'bg-gray-400 cursor-not-allowed pointer-events-none' 
                            : 'bg-indigo-600 hover:bg-indigo-700 active:scale-95' 
                       }}">
                        @if($isEmpty)
                            Korpa je prazna
                        @else
                            Isporuka
                        @endif
                    </a>
                </div>
            </section>
        </div>
    </div>
</div>