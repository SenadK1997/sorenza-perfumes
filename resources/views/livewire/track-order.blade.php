<div class="max-w-3xl mx-auto px-4 py-16 sm:px-6 lg:px-8">
    <div class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden">
        
        {{-- Header Section --}}
        <div class="bg-[#3D2206] px-8 py-10 text-center">
            <h2 class="text-3xl font-bold text-white font-serif">Prati svoju narudžbu</h2>
            <p class="mt-2 text-white/70">Unesite jedinstveni broj narudžbe koji ste dobili u potvrdi</p>
        </div>

        <div class="p-8 sm:p-12">
            <div class="space-y-6">
                <div>
                    <label for="pretty_id" class="block text-sm font-medium text-gray-700 uppercase tracking-wider">Broj Narudžbe</label>
                    <div class="mt-2 relative">
                        <input type="text" 
                            wire:model="pretty_id" 
                            id="pretty_id" 
                            class="block w-full rounded-2xl border-gray-200 p-4 pl-12 border focus:ring-[#DAAA57] focus:border-[#DAAA57] transition-all placeholder:text-gray-300 uppercase"
                            placeholder="Npr: SOR-XXXXX">
                        
                        {{-- Icon for visual cue --}}
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                            </svg>
                        </div>
                    </div>
                    @error('pretty_id') 
                        <span class="text-red-500 text-xs mt-2 block">{{ $message }}</span> 
                    @enderror
                </div>

                <button wire:click="findOrder" 
                    wire:loading.attr="disabled"
                    class="w-full bg-gradient-to-r from-[#3D2206] to-[#9D683D] text-white py-4 rounded-2xl font-bold hover:shadow-lg transition-all duration-300 flex justify-center items-center">
                    <span wire:loading.remove>Pronađi narudžbu</span>
                    <span wire:loading>Tražim...</span>
                </button>

                {{-- <p class="text-center text-xs text-gray-400">
                    Broj narudžbe možete pronaći u emailu koji vam je poslan nakon kupovine.
                </p> --}}
            </div>
        </div>
    </div>
</div>