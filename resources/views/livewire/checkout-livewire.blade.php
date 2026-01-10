<div class="bg-gray-50 py-16 min-h-screen">
    <div class="mx-auto max-w-7xl px-4 lg:px-8">
        <div class="lg:grid lg:grid-cols-12 lg:gap-x-12">
            
            <div class="lg:col-span-7">
                @if($step == 1)
                    <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-200">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">Vaši podaci</h2>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Email adresa</label>
                                <input type="email" wire:model="email" 
                                    class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-4 border">
                                @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <button wire:click="checkEmail" 
                                class="cursor-pointer w-full bg-indigo-600 text-white py-4 rounded-xl font-bold hover:bg-indigo-700 transition-all">
                                Nastavi na dostavu
                            </button>
                        </div>
                    </div>
                @else
                    <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-200">
                        <input type="hidden" wire:model="email">
                        <div class="hidden" aria-hidden="true">
                            <label for="extra_info_field">Dodatne napomene za dostavu</label>
                            <input type="text" 
                                wire:model="extra_info_field" 
                                id="extra_info_field" 
                                autocomplete="off" 
                                tabindex="-1">
                        </div>
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-2xl font-bold text-gray-900">Podaci za dostavu</h2>
                            <button wire:click="$set('step', 1)" class="cursor-pointer text-sm text-indigo-600 font-medium hover:underline">
                                Promijeni email
                            </button>
                        </div>

                        <div class="grid grid-cols-1 gap-y-6 sm:grid-cols-2 sm:gap-x-4">
                            <div class="sm:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Ime i prezime</label>
                                <input type="text" wire:model="full_name" class="mt-1 block w-full rounded-lg border-gray-300 border p-3">
                                @error('full_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Telefon</label>
                                <input 
                                    type="text" 
                                    inputmode="numeric"
                                    wire:model="phone" 
                                    maxlength="13"
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                    placeholder="061XXXXXX"
                                    class="mt-1 block w-full rounded-lg border-gray-300 border p-3"
                                >
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Kanton</label>
                                <select wire:model="canton" class="mt-1 block w-full rounded-lg border-gray-300 border p-3">
                                    <option value="">Odaberi kanton</option>
                                    @foreach($cantons as $c)
                                        <option value="{{ $c->value }}">{{ $c->label() }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="sm:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Adresa (Ulica i broj)</label>
                                <input type="text" wire:model="address_line_1" class="mt-1 block w-full rounded-lg border-gray-300 border p-3">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Grad</label>
                                <input type="text" wire:model="city" class="mt-1 block w-full rounded-lg border-gray-300 border p-3">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Poštanski broj</label>
                                <input type="text" wire:model="zipcode" class="mt-1 block w-full rounded-lg border-gray-300 border p-3">
                            </div>
                        </div>
                        @error('email') 
                            <div class="mt-4 p-3 bg-red-50 border border-red-200 rounded-lg text-red-600 text-sm text-center">
                                {{ $message }}
                            </div> 
                        @enderror

                        <button wire:click="placeOrder" 
                            class="cursor-pointer mt-10 w-full bg-indigo-600 text-white py-4 rounded-xl font-bold uppercase tracking-widest hover:bg-indigo-700 shadow-lg shadow-indigo-200 transition-all">
                            Završi narudžbu
                        </button>
                    </div>
                @endif
            </div>

            <div class="lg:col-span-5 mt-12 lg:mt-0">
                <div class="bg-white rounded-2xl p-6 border border-gray-200 sticky top-8">
                    <h2 class="text-lg font-bold mb-6">Pregled narudžbe</h2>
                    <ul class="divide-y divide-gray-100 overflow-auto max-h-96">
                        @foreach($items as $item)
                            <li class="flex py-4">
                                <img src="{{ Storage::url($item['main_image']) }}" class="h-16 w-16 rounded-md object-cover border">
                                <div class="ml-4 flex-1">
                                    <h3 class="text-sm font-medium">{{ $item['name'] }}</h3>
                                    <p class="text-xs text-gray-500">Količina: {{ $item['quantity'] }}</p>
                                    <p class="text-sm font-bold text-gray-900">{{ number_format($item['price'], 2) }} KM</p>
                                </div>
                            </li>
                        @endforeach
                    </ul>

                    <div class="mt-6 border-t pt-4 space-y-2">
                        {{-- Product Subtotal --}}
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Cijena proizvoda</span>
                            <span class="font-medium text-gray-900">{{ number_format($subtotal, 2) }} KM</span>
                        </div>
                    
                        {{-- Discount Row (Only shows if discount > 0) --}}
                        @if($discount > 0)
                            <div class="flex justify-between text-sm text-green-600 font-medium">
                                <span class="flex items-center gap-2">
                                    Popust 
                                    @if($coupon_code) 
                                        <span class="text-[10px] bg-green-100 px-2 py-0.5 rounded-full uppercase tracking-wider border border-green-200">
                                            {{ $coupon_code }}
                                        </span> 
                                    @endif
                                </span>
                                <span>- {{ number_format($discount, 2) }} KM</span>
                            </div>
                        @endif
                    
                        {{-- Shipping Row --}}
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Dostava</span>
                            <span class="font-medium {{ $shipping == 0 ? 'text-green-600' : 'text-gray-900' }}">
                                {{ $shipping == 0 ? 'Besplatno' : number_format($shipping, 2) . ' KM' }}
                            </span>
                        </div>
                    
                        {{-- Final Total --}}
                        <div class="flex justify-between text-xl font-bold border-t border-gray-100 pt-4 mt-4">
                            <span class="text-gray-900">Ukupno</span>
                            <span class="text-indigo-600">{{ number_format($total, 2) }} KM</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>