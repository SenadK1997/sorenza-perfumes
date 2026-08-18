<div class="min-h-[80vh] px-4 py-10"
     style="background:
        radial-gradient(circle at 10% 10%, rgba(255,153,180,0.28) 0%, transparent 45%),
        radial-gradient(circle at 90% 20%, rgba(180,150,255,0.28) 0%, transparent 45%),
        linear-gradient(135deg, #fdf7fb 0%, #f4f0ff 50%, #eef7fb 100%);
        background-attachment: fixed;">
    <div class="mx-auto max-w-lg">
        @include('livewire.customer.partials.header', ['customer' => $customer])

        <div class="mt-8 rounded-3xl bg-white/80 backdrop-blur border border-white/90 shadow-xl p-6 sm:p-8">
            <h2 class="font-serif italic text-2xl text-gray-900 mb-2">
                {{ $customer->password ? 'Promjena lozinke' : 'Postavljanje lozinke' }}
            </h2>
            <p class="text-sm text-gray-500 mb-6">
                @if($customer->password)
                    Unesite trenutnu i novu lozinku.
                @else
                    Postavite lozinku ako ne želite svaki put čekati link na email.
                @endif
            </p>

            @if(session('status'))
                <div class="mb-4 rounded-2xl border border-emerald-200 bg-emerald-50/70 px-4 py-3 text-sm text-emerald-800">
                    {{ session('status') }}
                </div>
            @endif

            <form wire:submit="save" class="space-y-4">
                @if($customer->password)
                    <div>
                        <label class="block text-xs uppercase tracking-[0.2em] text-gray-500 mb-1.5">Trenutna lozinka</label>
                        <input type="password" wire:model="current_password" autocomplete="current-password"
                               class="w-full rounded-full border-amber-200/80 bg-white px-4 py-2.5 text-sm">
                        @error('current_password') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                @endif

                <div>
                    <label class="block text-xs uppercase tracking-[0.2em] text-gray-500 mb-1.5">Nova lozinka</label>
                    <input type="password" wire:model="password" autocomplete="new-password"
                           class="w-full rounded-full border-amber-200/80 bg-white px-4 py-2.5 text-sm">
                    @error('password') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs uppercase tracking-[0.2em] text-gray-500 mb-1.5">Ponovi novu lozinku</label>
                    <input type="password" wire:model="password_confirmation" autocomplete="new-password"
                           class="w-full rounded-full border-amber-200/80 bg-white px-4 py-2.5 text-sm">
                </div>

                <button type="submit"
                        class="w-full rounded-full bg-gradient-to-r from-[#8b6914] via-[#BBA14F] to-[#DBC584] px-6 py-3 text-xs font-semibold uppercase tracking-[0.28em] text-white shadow-lg hover:opacity-95 transition">
                    Sačuvaj lozinku
                </button>
            </form>
        </div>
    </div>
</div>
