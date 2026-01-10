<div class="bg-gray-50 min-h-screen py-12">
    <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
        
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden mb-8">
            <div class="px-6 py-8 sm:p-10 text-center">
                <p class="text-sm font-semibold text-indigo-600 tracking-wide uppercase">Detalji narudžbe</p>
                <h1 class="mt-2 text-3xl font-bold text-gray-900 sm:text-4xl">{{ $order->pretty_id }}</h1>
                
                <div class="mt-6 flex justify-center">
                    <span class="inline-flex items-center rounded-full px-4 py-1 text-sm font-medium 
                        {{ match($order->status->value) {
                            'pending'   => 'bg-yellow-100 text-yellow-800',
                            'taken'     => 'bg-blue-100 text-blue-800',
                            'completed' => 'bg-green-100 text-green-700',
                            'cancelled' => 'bg-red-100 text-red-700',
                            default     => 'bg-gray-100 text-gray-800'
                        } }}">
                        
                        @switch($order->status->value)
                            @case('pending')
                                Na čekanju
                                @break
                            @case('taken')
                                Preuzeto
                                @break
                            @case('completed')
                                Završeno
                                @break
                            @case('cancelled')
                                Otkazano
                                @break
                            @default
                                {{ ucfirst($order->status->value) }}
                        @endswitch
                    </span>
                </div>
                <p class="mt-4 text-sm text-gray-500">
                    Datum narudžbe: {{ $order->created_at->format('d.m.Y') }}
                </p>

                @if($order->status->value === 'cancelled')
                    <p class="mt-2 text-xs text-red-600 font-medium">Ova narudžba je otkazana.</p>
                @endif
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden mb-8">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <h3 class="text-base font-semibold text-gray-900">Artikli</h3>
            </div>
            <ul class="divide-y divide-gray-100">
                @foreach($order->perfumes as $item)
                    <li class="p-6 flex items-center">
                        <div class="h-16 w-16 flex-shrink-0 overflow-hidden rounded-md border border-gray-200">
                            @if($item->main_image)
                                <img src="{{ Storage::url($item->main_image) }}" class="h-full w-full object-cover">
                            @else
                                <div class="h-full w-full bg-gray-100 flex items-center justify-center text-xs text-gray-400">IMG</div>
                            @endif
                        </div>
                        <div class="ml-4 flex-1">
                            <h4 class="text-sm font-medium text-gray-900">{{ $item->name }}</h4>
                            <p class="mt-1 text-xs text-gray-500">Količina: {{ $item->pivot->quantity }}</p>
                        </div>
                        <div class="text-sm font-medium text-gray-900">
                            {{ number_format($item->pivot->price * $item->pivot->quantity, 2) }} KM
                        </div>
                    </li>
                @endforeach
            </ul>
        
            {{-- Summary Section --}}
            <div class="bg-gray-50 px-6 py-6 border-t border-gray-200 space-y-3">
                
                {{-- Iznos Artikala (Subtotal) --}}
                <div class="flex justify-between text-sm text-gray-600">
                    <span>Iznos artikala:</span>
                    <span>{{ number_format($order->subtotal, 2) }} KM</span>
                </div>
        
                {{-- Popust (Shows only if there is a discount) --}}
                @if($order->discount_amount > 0)
                    <div class="flex justify-between text-sm text-green-600 font-medium">
                        <span>Popust @if($order->coupon_code) ({{ $order->coupon_code }}) @endif:</span>
                        <span>- {{ number_format($order->discount_amount, 2) }} KM</span>
                    </div>
                @endif
        
                {{-- Dostava --}}
                <div class="flex justify-between text-sm text-gray-600 pb-2">
                    <span>Dostava:</span>
                    <span class="{{ $order->shipping_fee == 0 ? 'text-green-600 font-medium' : '' }}">
                        {{ $order->shipping_fee == 0 ? 'Besplatna' : number_format($order->shipping_fee, 2) . ' KM' }}
                    </span>
                </div>
        
                {{-- Ukupno --}}
                <div class="flex justify-between text-lg font-bold text-gray-900 border-t border-gray-200 pt-4">
                    <span>Ukupno za platiti</span>
                    <span class="text-indigo-600">{{ number_format($order->amount, 2) }} KM</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <h3 class="text-base font-semibold text-gray-900">Podaci o dostavi (Zaštićeno)</h3>
            </div>
            <dl class="divide-y divide-gray-100">
                <div class="px-6 py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                    <dt class="text-sm font-medium text-gray-500">Ime kupca</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ \Illuminate\Support\Str::limit($order->full_name, 3, '****') }}
                    </dd>
                </div>
                <div class="px-6 py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                    <dt class="text-sm font-medium text-gray-500">Grad</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $order->city }}</dd>
                </div>
                <div class="px-6 py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                    <dt class="text-sm font-medium text-gray-500">Adresa</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ \Illuminate\Support\Str::limit($order->address_line_1, 3, '****') }}
                    </dd>
                </div>
                <div class="px-6 py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                    <dt class="text-sm font-medium text-gray-500">Telefon</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ \Illuminate\Support\Str::mask($order->phone, '*', 0, strlen($order->phone) - 3) }}
                    </dd>
                </div>
            </dl>
        </div>

        <div class="mt-8 text-center">
            <a href="/shop" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
                &larr; Nazad na shop
            </a>
        </div>

    </div>
</div>