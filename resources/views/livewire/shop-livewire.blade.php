<div class="bg-white">
    <!-- MOBILE FILTERS -->
    <!-- Mobile filter toggle button -->
    <!-- Mobile filter dialog -->
    <div id="mobile-filters" class="fixed inset-0 z-50 hidden lg:hidden">
        <!-- backdrop -->
        <div class="fixed inset-0 bg-black/25" onclick="toggleMobileFilters(false)"></div>
    
        <!-- panel -->
        <div class="fixed right-0 top-0 flex h-full w-[50%] max-w-md flex-col bg-white shadow-xl overflow-y-auto transition-transform transform translate-x-full" id="mobile-filters-panel">
        <div class="flex items-center justify-between p-4 border-b">
            <h2 class="text-lg font-medium text-gray-900">Filteri</h2>
            <button type="button" class="text-gray-400 hover:text-gray-600" onclick="toggleMobileFilters(false)">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
            </button>
        </div>
    
        <!-- Filters -->
        <div class="p-4 space-y-4">
            <!-- Gender -->
            <fieldset>
            <legend class="text-sm font-medium text-gray-900">Spol</legend>
            <div class="space-y-2 mt-2">
                <div class="flex items-center gap-2">
                <input type="checkbox" value="male" wire:model.live="gender" id="gender-male-mobile" class="h-5 w-5 rounded border-gray-300 checked:border-indigo-600 checked:bg-indigo-600">
                <label for="gender-male-mobile" class="text-sm text-gray-500">Muški</label>
                </div>
                <div class="flex items-center gap-2">
                <input type="checkbox" value="female" wire:model.live="gender" id="gender-female-mobile" class="h-5 w-5 rounded border-gray-300 checked:border-indigo-600 checked:bg-indigo-600">
                <label for="gender-female-mobile" class="text-sm text-gray-500">Ženski</label>
                </div>
                <div class="flex items-center gap-2">
                <input type="checkbox" value="unisex" wire:model.live="gender" id="gender-unisex-mobile" class="h-5 w-5 rounded border-gray-300 checked:border-indigo-600 checked:bg-indigo-600">
                <label for="gender-unisex-mobile" class="text-sm text-gray-500">Unisex</label>
                </div>
            </div>
            </fieldset>
    
            <!-- Price -->
            <fieldset>
            <legend class="text-sm font-medium text-gray-900">Cijena</legend>
            <div class="space-y-2 mt-2">
                @foreach([60, 80, 100, 120, 150] as $p)
                <div class="flex items-center gap-2">
                    <input type="checkbox" value="{{ $p }}" wire:model.live="price" id="price-{{ $p }}-mobile" class="h-5 w-5 rounded border-gray-300 checked:border-indigo-600 checked:bg-indigo-600">
                    <label for="price-{{ $p }}-mobile" class="text-sm text-gray-500">{{ $p }}</label>
                </div>
                @endforeach
            </div>
            </fieldset>
        </div>
        </div>
    </div>
    
    <!-- Toggle button -->
    <button class="lg:hidden inline-flex items-center p-2 border rounded" onclick="toggleMobileFilters(true)">
        Filteri
    </button>
  
    <!-- DESKTOP FILTERS + PRODUCTS -->
    <main class="mx-auto max-w-7xl px-4 py-4 lg:grid lg:grid-cols-4 lg:gap-x-8">
        <!-- Desktop filters -->
        <aside class="hidden lg:block space-y-6">
            <!-- Gender -->
            <fieldset>
                <legend class="block text-sm font-medium text-gray-900">Spol</legend>
                <div class="mt-4 space-y-3">
                    @foreach(['male' => 'Muški', 'female' => 'Ženski', 'unisex' => 'Unisex'] as $key => $label)
                        <div class="flex items-center gap-2">
                            <input type="checkbox" value="{{ $key }}" wire:model.live="gender" id="gender-{{ $key }}" class="h-5 w-5 rounded border-gray-300 checked:border-indigo-600 checked:bg-indigo-600">
                            <label for="gender-{{ $key }}" class="text-sm text-gray-500">{{ $label }}</label>
                        </div>
                    @endforeach
                </div>
            </fieldset>

            <!-- Price -->
            <fieldset>
                <legend class="block text-sm font-medium text-gray-900">Cijena</legend>
                <div class="mt-4 space-y-3">
                    @foreach([60, 80, 100, 120, 150] as $p)
                        <div class="flex items-center gap-2">
                            <input type="checkbox" value="{{ $p }}" wire:model.live="price" id="price-{{ $p }}" class="h-5 w-5 rounded border-gray-300 checked:border-indigo-600 checked:bg-indigo-600">
                            <label for="price-{{ $p }}" class="text-sm text-gray-500">{{ $p }}</label>
                        </div>
                    @endforeach
                </div>
            </fieldset>
        </aside>

        <!-- Perfume cards -->
        <div class="lg:col-span-3 grid grid-cols-1 py-6 sm:grid-cols-2 md:grid-cols-3 gap-6">
            @forelse($perfumes as $perfume)
                @include('components.perfume-card', ['perfume' => $perfume])
            @empty
                <p class="col-span-full text-center text-gray-500">Nema rezultata.</p>
            @endforelse
        </div>
    </main>
    <script>
        function toggleMobileFilters(show) {
          const backdrop = document.getElementById('mobile-filters');
          const panel = document.getElementById('mobile-filters-panel');
          if(show){
            backdrop.classList.remove('hidden');
            panel.classList.remove('translate-x-full');
            panel.classList.add('translate-x-0');
          } else {
            panel.classList.remove('translate-x-0');
            panel.classList.add('translate-x-full');
            setTimeout(()=> backdrop.classList.add('hidden'), 300);
          }
        }
        </script>
        <script>
            Livewire.on('updateUrl', url => {
                history.replaceState(null, null, url);
            });
        </script>
</div>
