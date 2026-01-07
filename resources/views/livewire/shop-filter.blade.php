<div class="bg-white">
    <!-- MOBILE FILTERS -->
    <el-dialog>
        <dialog id="mobile-filters" class="overflow-hidden backdrop:bg-transparent lg:hidden">
            <el-dialog-backdrop class="fixed inset-0 bg-black/25 transition-opacity duration-300 ease-linear data-closed:opacity-0"></el-dialog-backdrop>

            <div tabindex="0" class="fixed inset-0 flex focus:outline-none">
                <el-dialog-panel class="relative ml-auto flex w-full max-w-xs flex-col overflow-y-auto bg-white pt-4 pb-6 shadow-xl transition duration-300 ease-in-out data-closed:translate-x-full">
                    <div class="flex items-center justify-between px-4">
                        <h2 class="text-lg font-medium text-gray-900">Filteri</h2>
                        <button type="button" command="close" commandfor="mobile-filters" class="relative -mr-2 flex h-10 w-10 items-center justify-center rounded-md bg-white p-2 text-gray-400 hover:bg-gray-50 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                            <span class="sr-only">Close menu</span>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="h-6 w-6">
                                <path d="M6 18L18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </button>
                    </div>

                    <!-- FILTERS -->
                    <div class="mt-4 space-y-4 px-4">
                        <!-- GENDER -->
                        <fieldset>
                            <legend class="text-sm font-medium text-gray-900">Spol</legend>
                            <div class="mt-2 space-y-2">
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

                        <!-- PRICE -->
                        <fieldset>
                            <legend class="text-sm font-medium text-gray-900">Cijena</legend>
                            <div class="mt-2 space-y-2">
                                @foreach([60, 80, 100, 120, 150] as $p)
                                    <div class="flex items-center gap-2">
                                        <input type="checkbox" value="{{ $p }}" wire:model.live="price" id="price-{{ $p }}-mobile" class="h-5 w-5 rounded border-gray-300 checked:border-indigo-600 checked:bg-indigo-600">
                                        <label for="price-{{ $p }}-mobile" class="text-sm text-gray-500">{{ $p }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </fieldset>
                    </div>
                </el-dialog-panel>
            </div>
        </dialog>
    </el-dialog>

    <!-- DESKTOP FILTERS & PRODUCTS -->
    <main class="mx-auto max-w-7xl px-4 py-4 lg:grid lg:grid-cols-4 lg:gap-x-8">
        <aside class="hidden lg:block">
            <div class="space-y-6">
                <!-- GENDER -->
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

                <!-- PRICE -->
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
            </div>
        </aside>
    </main>
</div>
