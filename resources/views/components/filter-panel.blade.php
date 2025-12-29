<div class="bg-white">
    <!-- Mobile filter dialog -->
    <el-dialog>
        <dialog id="mobile-filters" class="overflow-hidden backdrop:bg-transparent lg:hidden">
          <el-dialog-backdrop class="fixed inset-0 bg-black/25 transition-opacity duration-300 ease-linear data-closed:opacity-0"></el-dialog-backdrop>
      
          <div tabindex="0" class="fixed inset-0 flex focus:outline-none">
            <el-dialog-panel class="relative ml-auto flex size-full max-w-xs transform flex-col overflow-y-auto bg-white pt-4 pb-6 shadow-xl transition duration-300 ease-in-out data-closed:translate-x-full">
              <div class="flex items-center justify-between px-4">
                <h2 class="text-lg font-medium text-gray-900">Filteri</h2>
                <button type="button" command="close" commandfor="mobile-filters" class="relative -mr-2 flex size-10 items-center justify-center rounded-md bg-white p-2 text-gray-400 hover:bg-gray-50 focus:ring-2 focus:ring-indigo-500 focus:outline-hidden">
                  <span class="sr-only">Close menu</span>
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="size-6">
                    <path d="M6 18 18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" />
                  </svg>
                </button>
              </div>
      
              <!-- Filters -->
              <form class="mt-4 space-y-4">
                <!-- Gender / Spol -->
                <fieldset class="border-t border-gray-200 pt-4">
                  <legend class="w-full px-2">
                    <button type="button" command="--toggle" commandfor="filter-section-gender" class="flex w-full items-center justify-between p-2 text-gray-400 hover:text-gray-500">
                      <span class="text-sm font-medium text-gray-900">Spol</span>
                      <svg viewBox="0 0 20 20" fill="currentColor" class="size-5 transform">
                        <path d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z" />
                      </svg>
                    </button>
                  </legend>
                  <el-disclosure id="filter-section-gender" hidden class="block px-4 pt-4 pb-2">
                    <div class="space-y-3">
                      <div class="flex items-center gap-3">
                        <input id="gender-male-mobile" type="checkbox" name="gender[]" value="male" class="h-5 w-5 rounded border-gray-300 checked:border-indigo-600 checked:bg-indigo-600" />
                        <label for="gender-male-mobile" class="text-sm text-gray-500">Muški</label>
                      </div>
                      <div class="flex items-center gap-3">
                        <input id="gender-female-mobile" type="checkbox" name="gender[]" value="female" class="h-5 w-5 rounded border-gray-300 checked:border-indigo-600 checked:bg-indigo-600" />
                        <label for="gender-female-mobile" class="text-sm text-gray-500">Ženski</label>
                      </div>
                      <div class="flex items-center gap-3">
                        <input id="gender-unisex-mobile" type="checkbox" name="gender[]" value="unisex" class="h-5 w-5 rounded border-gray-300 checked:border-indigo-600 checked:bg-indigo-600" />
                        <label for="gender-unisex-mobile" class="text-sm text-gray-500">Unisex</label>
                      </div>
                    </div>
                  </el-disclosure>
                </fieldset>
      
                <!-- Price / Cijena -->
                <fieldset class="border-t border-gray-200 pt-4">
                  <legend class="w-full px-2">
                    <button type="button" command="--toggle" commandfor="filter-section-price" class="flex w-full items-center justify-between p-2 text-gray-400 hover:text-gray-500">
                      <span class="text-sm font-medium text-gray-900">Cijena</span>
                      <svg viewBox="0 0 20 20" fill="currentColor" class="size-5 transform">
                        <path d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z" />
                      </svg>
                    </button>
                  </legend>
                  <el-disclosure id="filter-section-price" hidden class="block px-4 pt-4 pb-2">
                    <div class="space-y-3">
                      <div class="flex items-center gap-3">
                        <input id="price-60-mobile" type="checkbox" name="price[]" value="60" class="h-5 w-5 rounded border-gray-300 checked:border-indigo-600 checked:bg-indigo-600" />
                        <label for="price-60-mobile" class="text-sm text-gray-500">60</label>
                      </div>
                      <div class="flex items-center gap-3">
                        <input id="price-80-mobile" type="checkbox" name="price[]" value="80" class="h-5 w-5 rounded border-gray-300 checked:border-indigo-600 checked:bg-indigo-600" />
                        <label for="price-80-mobile" class="text-sm text-gray-500">80</label>
                      </div>
                      <div class="flex items-center gap-3">
                        <input id="price-100-mobile" type="checkbox" name="price[]" value="100" class="h-5 w-5 rounded border-gray-300 checked:border-indigo-600 checked:bg-indigo-600" />
                        <label for="price-100-mobile" class="text-sm text-gray-500">100</label>
                      </div>
                      <div class="flex items-center gap-3">
                        <input id="price-120-mobile" type="checkbox" name="price[]" value="120" class="h-5 w-5 rounded border-gray-300 checked:border-indigo-600 checked:bg-indigo-600" />
                        <label for="price-120-mobile" class="text-sm text-gray-500">120</label>
                      </div>
                      <div class="flex items-center gap-3">
                        <input id="price-150-mobile" type="checkbox" name="price[]" value="150" class="h-5 w-5 rounded border-gray-300 checked:border-indigo-600 checked:bg-indigo-600" />
                        <label for="price-150-mobile" class="text-sm text-gray-500">150</label>
                      </div>
                    </div>
                  </el-disclosure>
                </fieldset>
              </form>
            </el-dialog-panel>
          </div>
        </dialog>
      </el-dialog>
      
  
    <main class="mx-auto max-w-2xl px-4 py-4 sm:px-6 sm:py-4 lg:max-w-7xl lg:px-8">
      <div class="border-b border-gray-200 pb-4">
        <h1 class="text-4xl font-bold tracking-tight text-gray-900">Filteri</h1>
      </div>
  
      <div class="pt-6 lg:grid lg:grid-cols-3 lg:gap-x-8 xl:grid-cols-4">
        <aside>
          <h2 class="sr-only">Filters</h2>
  
          <button type="button" command="show-modal" commandfor="mobile-filters" class="inline-flex items-center lg:hidden">
            <span class="text-sm font-medium text-gray-700">Filters</span>
            <svg viewBox="0 0 20 20" fill="currentColor" data-slot="icon" aria-hidden="true" class="ml-1 size-5 shrink-0 text-gray-400">
              <path d="M10.75 4.75a.75.75 0 0 0-1.5 0v4.5h-4.5a.75.75 0 0 0 0 1.5h4.5v4.5a.75.75 0 0 0 1.5 0v-4.5h4.5a.75.75 0 0 0 0-1.5h-4.5v-4.5Z" />
            </svg>
          </button>
  
          <div class="hidden lg:block">
            <form class="divide-y divide-gray-200">
              <div class="py-4 first:pt-0 last:pb-0">
                <fieldset>
                  <legend class="block text-sm font-medium text-gray-900">Spol</legend>
                  <div class="space-y-3 pt-6">
                    <div class="flex gap-3">
                      <div class="flex h-5 shrink-0 items-center">
                        <div class="group grid size-4 grid-cols-1">
                          <input id="color-0" type="checkbox" name="color[]" value="white" class="col-start-1 row-start-1 appearance-none rounded-sm border border-gray-300 bg-white checked:border-indigo-600 checked:bg-indigo-600 indeterminate:border-indigo-600 indeterminate:bg-indigo-600 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 disabled:border-gray-300 disabled:bg-gray-100 disabled:checked:bg-gray-100 forced-colors:appearance-auto" />
                          <svg viewBox="0 0 14 14" fill="none" class="pointer-events-none col-start-1 row-start-1 size-3.5 self-center justify-self-center stroke-white group-has-disabled:stroke-gray-950/25">
                            <path d="M3 8L6 11L11 3.5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="opacity-0 group-has-checked:opacity-100" />
                            <path d="M3 7H11" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="opacity-0 group-has-indeterminate:opacity-100" />
                          </svg>
                        </div>
                      </div>
                      <label for="color-0" class="text-sm text-gray-600">Muški</label>
                    </div>
                    <div class="flex gap-3">
                      <div class="flex h-5 shrink-0 items-center">
                        <div class="group grid size-4 grid-cols-1">
                          <input id="color-1" type="checkbox" name="color[]" value="beige" class="col-start-1 row-start-1 appearance-none rounded-sm border border-gray-300 bg-white checked:border-indigo-600 checked:bg-indigo-600 indeterminate:border-indigo-600 indeterminate:bg-indigo-600 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 disabled:border-gray-300 disabled:bg-gray-100 disabled:checked:bg-gray-100 forced-colors:appearance-auto" />
                          <svg viewBox="0 0 14 14" fill="none" class="pointer-events-none col-start-1 row-start-1 size-3.5 self-center justify-self-center stroke-white group-has-disabled:stroke-gray-950/25">
                            <path d="M3 8L6 11L11 3.5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="opacity-0 group-has-checked:opacity-100" />
                            <path d="M3 7H11" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="opacity-0 group-has-indeterminate:opacity-100" />
                          </svg>
                        </div>
                      </div>
                      <label for="color-1" class="text-sm text-gray-600">Ženski</label>
                    </div>
                    <div class="flex gap-3">
                      <div class="flex h-5 shrink-0 items-center">
                        <div class="group grid size-4 grid-cols-1">
                          <input id="color-2" type="checkbox" name="color[]" value="blue" class="col-start-1 row-start-1 appearance-none rounded-sm border border-gray-300 bg-white checked:border-indigo-600 checked:bg-indigo-600 indeterminate:border-indigo-600 indeterminate:bg-indigo-600 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 disabled:border-gray-300 disabled:bg-gray-100 disabled:checked:bg-gray-100 forced-colors:appearance-auto" />
                          <svg viewBox="0 0 14 14" fill="none" class="pointer-events-none col-start-1 row-start-1 size-3.5 self-center justify-self-center stroke-white group-has-disabled:stroke-gray-950/25">
                            <path d="M3 8L6 11L11 3.5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="opacity-0 group-has-checked:opacity-100" />
                            <path d="M3 7H11" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="opacity-0 group-has-indeterminate:opacity-100" />
                          </svg>
                        </div>
                      </div>
                      <label for="color-2" class="text-sm text-gray-600">Unisex</label>
                    </div>
                  </div>
                </fieldset>
              </div>
              <div class="py-4 first:pt-0 last:pb-0">
                <fieldset>
                  <legend class="block text-sm font-medium text-gray-900">Cijena</legend>
                  <div class="space-y-3 pt-6">
                    <div class="flex gap-3">
                      <div class="flex h-5 shrink-0 items-center">
                        <div class="group grid size-4 grid-cols-1">
                          <input id="category-0" type="checkbox" name="category[]" value="new-arrivals" class="col-start-1 row-start-1 appearance-none rounded-sm border border-gray-300 bg-white checked:border-indigo-600 checked:bg-indigo-600 indeterminate:border-indigo-600 indeterminate:bg-indigo-600 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 disabled:border-gray-300 disabled:bg-gray-100 disabled:checked:bg-gray-100 forced-colors:appearance-auto" />
                          <svg viewBox="0 0 14 14" fill="none" class="pointer-events-none col-start-1 row-start-1 size-3.5 self-center justify-self-center stroke-white group-has-disabled:stroke-gray-950/25">
                            <path d="M3 8L6 11L11 3.5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="opacity-0 group-has-checked:opacity-100" />
                            <path d="M3 7H11" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="opacity-0 group-has-indeterminate:opacity-100" />
                          </svg>
                        </div>
                      </div>
                      <label for="category-0" class="text-sm text-gray-600">60</label>
                    </div>
                    <div class="flex gap-3">
                      <div class="flex h-5 shrink-0 items-center">
                        <div class="group grid size-4 grid-cols-1">
                          <input id="category-1" type="checkbox" name="category[]" value="tees" class="col-start-1 row-start-1 appearance-none rounded-sm border border-gray-300 bg-white checked:border-indigo-600 checked:bg-indigo-600 indeterminate:border-indigo-600 indeterminate:bg-indigo-600 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 disabled:border-gray-300 disabled:bg-gray-100 disabled:checked:bg-gray-100 forced-colors:appearance-auto" />
                          <svg viewBox="0 0 14 14" fill="none" class="pointer-events-none col-start-1 row-start-1 size-3.5 self-center justify-self-center stroke-white group-has-disabled:stroke-gray-950/25">
                            <path d="M3 8L6 11L11 3.5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="opacity-0 group-has-checked:opacity-100" />
                            <path d="M3 7H11" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="opacity-0 group-has-indeterminate:opacity-100" />
                          </svg>
                        </div>
                      </div>
                      <label for="category-1" class="text-sm text-gray-600">80</label>
                    </div>
                    <div class="flex gap-3">
                      <div class="flex h-5 shrink-0 items-center">
                        <div class="group grid size-4 grid-cols-1">
                          <input id="category-2" type="checkbox" name="category[]" value="crewnecks" class="col-start-1 row-start-1 appearance-none rounded-sm border border-gray-300 bg-white checked:border-indigo-600 checked:bg-indigo-600 indeterminate:border-indigo-600 indeterminate:bg-indigo-600 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 disabled:border-gray-300 disabled:bg-gray-100 disabled:checked:bg-gray-100 forced-colors:appearance-auto" />
                          <svg viewBox="0 0 14 14" fill="none" class="pointer-events-none col-start-1 row-start-1 size-3.5 self-center justify-self-center stroke-white group-has-disabled:stroke-gray-950/25">
                            <path d="M3 8L6 11L11 3.5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="opacity-0 group-has-checked:opacity-100" />
                            <path d="M3 7H11" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="opacity-0 group-has-indeterminate:opacity-100" />
                          </svg>
                        </div>
                      </div>
                      <label for="category-2" class="text-sm text-gray-600">100</label>
                    </div>
                    <div class="flex gap-3">
                      <div class="flex h-5 shrink-0 items-center">
                        <div class="group grid size-4 grid-cols-1">
                          <input id="category-3" type="checkbox" name="category[]" value="sweatshirts" class="col-start-1 row-start-1 appearance-none rounded-sm border border-gray-300 bg-white checked:border-indigo-600 checked:bg-indigo-600 indeterminate:border-indigo-600 indeterminate:bg-indigo-600 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 disabled:border-gray-300 disabled:bg-gray-100 disabled:checked:bg-gray-100 forced-colors:appearance-auto" />
                          <svg viewBox="0 0 14 14" fill="none" class="pointer-events-none col-start-1 row-start-1 size-3.5 self-center justify-self-center stroke-white group-has-disabled:stroke-gray-950/25">
                            <path d="M3 8L6 11L11 3.5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="opacity-0 group-has-checked:opacity-100" />
                            <path d="M3 7H11" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="opacity-0 group-has-indeterminate:opacity-100" />
                          </svg>
                        </div>
                      </div>
                      <label for="category-3" class="text-sm text-gray-600">120</label>
                    </div>
                    <div class="flex gap-3">
                      <div class="flex h-5 shrink-0 items-center">
                        <div class="group grid size-4 grid-cols-1">
                          <input id="category-4" type="checkbox" name="category[]" value="pants-shorts" class="col-start-1 row-start-1 appearance-none rounded-sm border border-gray-300 bg-white checked:border-indigo-600 checked:bg-indigo-600 indeterminate:border-indigo-600 indeterminate:bg-indigo-600 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 disabled:border-gray-300 disabled:bg-gray-100 disabled:checked:bg-gray-100 forced-colors:appearance-auto" />
                          <svg viewBox="0 0 14 14" fill="none" class="pointer-events-none col-start-1 row-start-1 size-3.5 self-center justify-self-center stroke-white group-has-disabled:stroke-gray-950/25">
                            <path d="M3 8L6 11L11 3.5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="opacity-0 group-has-checked:opacity-100" />
                            <path d="M3 7H11" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="opacity-0 group-has-indeterminate:opacity-100" />
                          </svg>
                        </div>
                      </div>
                      <label for="category-4" class="text-sm text-gray-600">150</label>
                    </div>
                  </div>
                </fieldset>
              </div>
            </form>
          </div>
        </aside>
  
        <!-- Product grid -->
        <div class="mt-6 lg:col-span-2 lg:mt-0 xl:col-span-3">
          <!-- Your content -->
        </div>
      </div>
    </main>
  </div>
  