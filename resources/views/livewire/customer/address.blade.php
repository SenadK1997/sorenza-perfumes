<div class="min-h-[80vh] px-4 py-10"
     style="background:
        radial-gradient(circle at 10% 10%, rgba(255,153,180,0.28) 0%, transparent 45%),
        radial-gradient(circle at 90% 20%, rgba(180,150,255,0.28) 0%, transparent 45%),
        linear-gradient(135deg, #fdf7fb 0%, #f4f0ff 50%, #eef7fb 100%);
        background-attachment: fixed;">
    <div class="mx-auto max-w-3xl">
        @include('livewire.customer.partials.header', ['customer' => $customer])

        <div class="mt-8 rounded-3xl bg-white/80 backdrop-blur border border-white/90 shadow-xl p-6 sm:p-8">
            <h2 class="font-serif italic text-2xl text-gray-900 mb-2">Vaša adresa</h2>
            <p class="text-sm text-gray-500 mb-6">Ova adresa se automatski koristi na kasi.</p>

            @if(session('status'))
                <div class="mb-4 rounded-2xl border border-emerald-200 bg-emerald-50/70 px-4 py-3 text-sm text-emerald-800">
                    {{ session('status') }}
                </div>
            @endif

            <form wire:submit="save" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-xs uppercase tracking-[0.2em] text-gray-500 mb-1.5">Ime i prezime</label>
                    <input type="text" wire:model="full_name" class="w-full rounded-full border-amber-200/80 bg-white px-4 py-2.5 text-sm">
                    @error('full_name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs uppercase tracking-[0.2em] text-gray-500 mb-1.5">Telefon</label>
                    <input type="text" wire:model="phone" class="w-full rounded-full border-amber-200/80 bg-white px-4 py-2.5 text-sm">
                    @error('phone') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs uppercase tracking-[0.2em] text-gray-500 mb-1.5">Poštanski broj</label>
                    <input type="text" wire:model="zipcode" class="w-full rounded-full border-amber-200/80 bg-white px-4 py-2.5 text-sm">
                    @error('zipcode') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs uppercase tracking-[0.2em] text-gray-500 mb-1.5">Adresa</label>
                    <input type="text" wire:model="address_line_1" class="w-full rounded-full border-amber-200/80 bg-white px-4 py-2.5 text-sm">
                    @error('address_line_1') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs uppercase tracking-[0.2em] text-gray-500 mb-1.5">Adresa (drugi red)</label>
                    <input type="text" wire:model="address_line_2" class="w-full rounded-full border-amber-200/80 bg-white px-4 py-2.5 text-sm">
                </div>

                <div>
                    <label class="block text-xs uppercase tracking-[0.2em] text-gray-500 mb-1.5">Grad</label>
                    <input type="text" wire:model="city" class="w-full rounded-full border-amber-200/80 bg-white px-4 py-2.5 text-sm">
                    @error('city') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs uppercase tracking-[0.2em] text-gray-500 mb-1.5">Kanton</label>
                    <select wire:model="canton" class="w-full rounded-full border-amber-200/80 bg-white px-4 py-2.5 text-sm">
                        <option value="">— Izaberite kanton —</option>
                        @foreach($cantons as $c)
                            <option value="{{ $c->value }}">{{ $c->label() }}</option>
                        @endforeach
                    </select>
                    @error('canton') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="md:col-span-2 pt-2">
                    <button type="submit"
                            class="rounded-full bg-gradient-to-r from-[#8b6914] via-[#BBA14F] to-[#DBC584] px-6 py-3 text-xs font-semibold uppercase tracking-[0.28em] text-white shadow-lg hover:opacity-95 transition">
                        Sačuvaj
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
