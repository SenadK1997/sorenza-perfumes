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
                            <p class="mt-1 text-xs text-gray-500">
                                {{ $item->pivot->quantity }} × {{ number_format($item->pivot->price, 2) }} KM
                            </p>
                        </div>
                        <div class="text-sm font-medium text-gray-900 tabular-nums">
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
        
                {{-- Popust na iznos korpe (automatski, ne kupon) --}}
                @if(($order->tier_discount_amount ?? 0) > 0)
                    <div class="flex justify-between text-sm text-rose-600 font-medium">
                        <span>Popust na iznos korpe:</span>
                        <span>- {{ number_format($order->tier_discount_amount, 2) }} KM</span>
                    </div>
                @endif

                {{-- Loyalty popust --}}
                @if(($order->loyalty_discount_amount ?? 0) > 0)
                    <div class="flex justify-between text-sm text-violet-600 font-medium">
                        <span>
                            Nivo {{ $order->loyalty_tier ?? 'Bronze' }} — loyalty popust:
                        </span>
                        <span>- {{ number_format($order->loyalty_discount_amount, 2) }} KM</span>
                    </div>
                @endif

                {{-- Popust putem kupona --}}
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

        {{-- REFUND SECTION --}}
        <div class="mt-8 bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-amber-50 to-rose-50">
                <div class="flex items-center gap-3">
                    <span class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-white/80 ring-1 ring-amber-200 text-amber-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h13a5 5 0 010 10h-1M3 10l4-4M3 10l4 4"/>
                        </svg>
                    </span>
                    <div>
                        <h3 class="text-base font-semibold text-gray-900">Povrat sredstava</h3>
                        <p class="text-xs text-gray-500">Besplatni povrat u roku od {{ $this->refundDays }} dana</p>
                    </div>
                </div>
            </div>

            <div class="p-6 space-y-4">
                @if(session('refund_success'))
                    <div class="rounded-xl bg-green-50 border border-green-200 p-4 text-sm text-green-800">
                        {{ session('refund_success') }}
                    </div>
                @endif

                @if($this->existingRefund)
                    <div class="rounded-xl border border-gray-200 bg-gray-50 p-5">
                        <div class="flex items-center justify-between flex-wrap gap-2">
                            <div class="text-sm font-medium text-gray-900">Postoji zahtjev za povrat</div>
                            <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold
                                @switch($this->existingRefund->status->value)
                                    @case('pending')  bg-yellow-100 text-yellow-800 @break
                                    @case('approved') bg-blue-100 text-blue-800 @break
                                    @case('rejected') bg-red-100 text-red-800 @break
                                    @case('refunded') bg-green-100 text-green-800 @break
                                @endswitch">
                                {{ $this->existingRefund->status->label() }}
                            </span>
                        </div>
                        <p class="mt-3 text-xs uppercase tracking-widest text-gray-500">Vaš razlog</p>
                        <p class="text-sm text-gray-800 whitespace-pre-line">{{ $this->existingRefund->reason }}</p>

                        @if($this->existingRefund->admin_response)
                            <p class="mt-4 text-xs uppercase tracking-widest text-gray-500">Odgovor podrške</p>
                            <p class="text-sm text-gray-800 whitespace-pre-line">{{ $this->existingRefund->admin_response }}</p>
                        @endif

                        <p class="mt-4 text-xs text-gray-400">
                            Poslano: {{ $this->existingRefund->created_at->format('d.m.Y H:i') }}
                        </p>
                    </div>
                @elseif($this->canRequestRefund())
                    @if(! $showRefundForm)
                        <div class="flex items-center justify-between flex-wrap gap-3">
                            <p class="text-sm text-gray-600">
                                Niste zadovoljni proizvodom? Možete zatražiti povrat u roku od
                                <strong>{{ $this->refundDays }} dana</strong>.
                            </p>
                            <button wire:click="toggleRefundForm"
                                    class="inline-flex items-center gap-2 rounded-full border border-amber-800/70 bg-white px-5 py-2 text-xs font-medium uppercase tracking-[0.25em] text-amber-900 hover:bg-gradient-to-r hover:from-[#BBA14F] hover:to-[#DBC584] hover:text-white hover:border-transparent transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h13a5 5 0 010 10h-1M3 10l4-4M3 10l4 4"/>
                                </svg>
                                Zatraži povrat
                            </button>
                        </div>
                    @else
                        <form wire:submit.prevent="submitRefund" class="space-y-3">
                            <label class="block text-sm font-medium text-gray-700">Razlog za povrat</label>
                            <textarea wire:model="refundReason" rows="4"
                                      class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-amber-600 focus:ring-amber-600 sm:text-sm"
                                      placeholder="Opišite ukratko razlog povrata (min. 10 karaktera)"></textarea>
                            @error('refundReason') <p class="text-xs text-red-600">{{ $message }}</p> @enderror

                            <div class="flex items-center gap-2">
                                <button type="submit"
                                        class="inline-flex items-center gap-2 rounded-full bg-gradient-to-r from-[#8b6914] via-[#BBA14F] to-[#DBC584] px-6 py-2.5 text-xs font-semibold uppercase tracking-[0.25em] text-white shadow hover:opacity-95 transition">
                                    Pošalji zahtjev
                                </button>
                                <button type="button" wire:click="toggleRefundForm"
                                        class="text-xs uppercase tracking-widest text-gray-500 hover:text-gray-900">
                                    Otkaži
                                </button>
                            </div>
                        </form>
                    @endif
                @else
                    <p class="text-sm text-gray-500">
                        @if($this->order->status?->value === 'cancelled')
                            Ova narudžba je otkazana pa nije moguće zatražiti povrat.
                        @elseif(in_array($this->order->status?->value, ['pending', 'taken']))
                            Povrat možete zatražiti tek kada narudžba bude označena kao <strong>Završeno</strong> (dostavljeno).
                        @else
                            Prozor za povrat od {{ $this->refundDays }} dana je istekao.
                        @endif
                    </p>
                @endif
            </div>
        </div>

        <div class="mt-8 text-center">
            <a href="/shop" class="text-sm font-medium text-amber-800 hover:text-amber-900 tracking-widest uppercase">
                &larr; Nazad na shop
            </a>
        </div>

    </div>
</div>