<div class="bg-white py-8 sm:py-12">
    <div class="mx-auto max-w-3xl px-4 text-center sm:px-6 lg:px-8">
        <div class="inline-flex items-center justify-center w-20 h-20 bg-green-100 rounded-full mb-8">
            <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>

        <h1 class="text-4xl font-bold tracking-tight text-gray-900 sm:text-5xl">Hvala na narudžbi!</h1>
        <p class="mt-4 text-lg text-gray-500">Vaša narudžba je primljena i trenutno se obrađuje.</p>

        <div class="mt-12 bg-gray-50 rounded-2xl border border-gray-100 overflow-hidden">
            <div class="bg-gray-100 px-8 py-4 border-b border-gray-200">
                <h2 class="text-xs font-semibold uppercase tracking-wide text-gray-500">Broj narudžbe</h2>
                
                <p class="text-2xl font-extrabold text-gray-900">
                    <a href="{{ route('order.track', $order->pretty_id) }}" 
                       class="text-indigo-600 hover:text-indigo-500 hover:underline decoration-2 underline-offset-2 transition-all"
                       title="Klikni za detalje">
                        {{ $order->pretty_id }}
                    </a>
                </p>
                <p class="text-xs text-gray-400 mt-1">Kliknite na broj iznad za praćenje narudžbe</p>
            </div>
            
            <div class="p-8 space-y-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 text-left">
                    <div>
                        <h3 class="text-sm font-bold text-gray-900 uppercase">Detalji isporuke</h3>
                        <address class="mt-3 not-italic text-sm text-gray-600 leading-relaxed">
                            <span class="block font-bold text-gray-800">{{ $order->full_name }}</span>
                            <span class="block">{{ $order->address_line_1 }}</span>
                            <span class="block">{{ $order->zipcode }}, {{ $order->city }}</span>
                            <span class="block">{{ is_string($order->canton) ? $order->canton : $order->canton->label() }}</span>
                            <span class="block mt-2 font-medium">{{ $order->phone }}</span>
                        </address>
                    </div>

                    <div class="mt-3 space-y-2">
                        <div class="flex justify-between text-sm text-gray-600">
                            <span>Iznos artikala:</span>
                            <span>{{ number_format($order->subtotal, 2) }} KM</span>
                        </div>
                    
                        @if($order->discount_amount > 0)
                            <div class="flex justify-between text-sm text-green-600">
                                <span>Popust ({{ $order->coupon_code }}):</span>
                                <span>- {{ number_format($order->discount_amount, 2) }} KM</span>
                            </div>
                        @endif
                    
                        <div class="flex justify-between text-sm text-gray-600">
                            <span>Dostava:</span>
                            <span>{{ $order->shipping_fee == 0 ? 'Besplatna' : '10.00 KM' }}</span>
                        </div>
                    
                        <div class="flex justify-between text-base font-bold text-gray-900 border-t pt-2 mt-2">
                            <span>Ukupno za platiti:</span>
                            <span class="text-indigo-600">{{ number_format($order->amount, 2) }} KM</span>
                        </div>
                    </div>
                </div>

                <div class="border-t border-gray-200 pt-6 text-left">
                    <h3 class="text-sm font-bold text-gray-900 uppercase mb-4">Naručeni artikli</h3>
                    <ul class="divide-y divide-gray-200">
                        @foreach($order->perfumes as $perfume)
                        <li class="py-3 flex justify-between text-sm">
                            <span class="text-gray-600">{{ $perfume->name }} <span class="font-bold">x{{ $perfume->pivot->quantity }}</span></span>
                            <span class="font-medium text-gray-900">{{ number_format($perfume->pivot->price * $perfume->pivot->quantity, 2) }} KM</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <div class="mt-10 flex items-center justify-center gap-x-6">
            <a href="/shop" class="rounded-md bg-indigo-600 px-6 py-3 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition-all">
                Nazad na shop
            </a>
        </div>
    </div>
</div>