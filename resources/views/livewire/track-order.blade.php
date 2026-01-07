<div class="max-w-3xl mx-auto px-4 py-16 sm:px-6 lg:px-8">
    <div class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden">
        
        <div class="bg-[#3D2206] px-8 py-10 text-center">
            <h2 class="text-3xl font-bold text-white font-serif">Prati svoju narudžbu</h2>
            <p class="mt-2 text-white/70">Unesite email adresu kojom ste izvršili kupovinu</p>
        </div>

        <div class="p-8 sm:p-12">
            @if($step == 1)
                <div class="space-y-6">
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email adresa</label>
                        <input type="email" wire:model="email" id="email" 
                            class="mt-2 block w-full rounded-2xl border-gray-200 p-4 border focus:ring-[#DAAA57] focus:border-[#DAAA57] transition-all"
                            placeholder="primjer@email.com">
                        @error('email') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <button wire:click="findOrders" 
                        class="w-full bg-gradient-to-r from-[#3D2206] to-[#9D683D] text-white py-4 rounded-2xl font-bold hover:shadow-lg transition-all duration-300">
                        Pronađi narudžbe
                    </button>
                </div>
            @else
                <div class="space-y-6">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-bold text-gray-900">Pronađene narudžbe ({{ count($orders) }})</h3>
                        <button wire:click="$set('step', 1)" class="text-sm text-[#9D683D] font-medium hover:underline">
                            Promijeni email
                        </button>
                    </div>

                    @if(count($orders) > 0)
                        <div class="grid gap-4">
                            @foreach($orders as $order)
                                <a href="{{ route('order.track', $order->pretty_id) }}" 
                                class="group flex items-center justify-between p-5 rounded-2xl border border-gray-100 bg-gray-50 hover:bg-white hover:border-[#DAAA57] hover:shadow-md transition-all">
                                    <div class="flex items-center gap-4">
                                        <div class="p-3 rounded-xl bg-white text-[#3D2206] group-hover:bg-[#3D2206] group-hover:text-white transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-bold text-gray-900 group-hover:text-[#9D683D] transition-colors">{{ $order->pretty_id }}</p>
                                            <div class="flex items-center gap-2 mt-0.5">
                                                <p class="text-xs text-gray-500">{{ $order->created_at->format('d.m.Y') }}</p>
                                                <span class="text-gray-300">•</span>
                                                <div class="flex items-center gap-1.5">
                                                    <span class="h-1.5 w-1.5 rounded-full {{ match($order->status->value) {
                                                        'pending' => 'bg-yellow-500',
                                                        'taken' => 'bg-blue-500',
                                                        'completed' => 'bg-green-500',
                                                        'cancelled' => 'bg-red-500',
                                                        default => 'bg-gray-500'
                                                    } }}"></span>
                                                    <p class="text-xs font-medium text-gray-700">{{ $order->status->translatedLabel() }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <span class="text-sm font-semibold text-gray-900">{{ number_format($order->amount, 2) }} KM</span>
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 text-gray-400">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                                        </svg>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-10">
                            <p class="text-gray-500">Nismo pronašli nijednu narudžbu za ovaj email.</p>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>