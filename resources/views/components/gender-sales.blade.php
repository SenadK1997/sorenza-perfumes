<div class="relative">
    <!-- Background image and overlap -->
    <div aria-hidden="true" class="absolute inset-0 hidden sm:flex sm:flex-col">
      <div class="relative w-full flex-1">
        <div class="absolute inset-0 overflow-hidden">
          {{-- <img src="https://tailwindcss.com/plus-assets/img/ecommerce-images/home-page-04-hero-full-width.jpg" alt="" class="size-full object-cover" /> --}}
        </div>
        <div class="absolute inset-0 opacity-50"></div>
      </div>
      <div class="h-32 w-full bg-white md:h-40 lg:h-48"></div>
    </div>
  
    <div class="relative mx-auto max-w-3xl px-4 pb-96 text-center sm:px-6 sm:pb-0 lg:px-8">
      <!-- Background image and overlap -->
      <div aria-hidden="true" class="absolute inset-0 flex flex-col sm:hidden">
        <div class="relative w-full flex-1">
          <div class="absolute inset-0 overflow-hidden">
            <img src="https://tailwindcss.com/plus-assets/img/ecommerce-images/home-page-04-hero-full-width.jpg" alt="" class="size-full object-cover" />
          </div>
          <div class="absolute inset-0 opacity-50"></div>
        </div>
        <div class="h-48 w-full bg-white"></div>
      </div>
    </div>
  
    <section aria-labelledby="collection-heading" class="relative -mt-96 sm:mt-0">
      <h2 id="collection-heading" class="sr-only">Collections</h2>
      <div class="mx-auto grid max-w-md grid-cols-1 gap-y-6 px-4 sm:max-w-7xl sm:grid-cols-3 sm:gap-x-6 sm:gap-y-0 sm:px-6 lg:gap-x-8 lg:px-8">
        <div class="group relative h-96 rounded-lg bg-white shadow-xl sm:aspect-4/5 sm:h-auto">
          <div aria-hidden="true" class="absolute inset-0 overflow-hidden rounded-lg">
            <div class="absolute inset-0 overflow-hidden group-hover:opacity-75">
              <img 
                src="{{ asset('storage/images/womanperfume.jpg') }}" 
                alt="Ženski parfem" 
                class="size-full object-cover" />
            </div>
            <div class="absolute inset-0 bg-linear-to-b from-transparent to-black opacity-50"></div>
          </div>
          <div class="absolute inset-0 flex items-end rounded-lg p-6">
            <div>
              <p aria-hidden="true" class="text-sm text-white">Pogledaj kolekciju</p>
              <h3 class="mt-1 font-semibold text-white">
                <a href="#">
                  <span class="absolute inset-0"></span>
                  Ženski parfemi
                </a>
              </h3>
            </div>
          </div>
        </div>
        <div class="group relative h-96 rounded-lg bg-white shadow-xl sm:aspect-4/5 sm:h-auto">
          <div aria-hidden="true" class="absolute inset-0 overflow-hidden rounded-lg">
            <div class="absolute inset-0 overflow-hidden group-hover:opacity-75">
              <img 
                src="{{ asset('storage/images/manperfume.jpg') }}"
                alt="Man wearing a charcoal gray cotton t-shirt." 
                class="size-full object-cover" />
            </div>
            <div class="absolute inset-0 bg-linear-to-b from-transparent to-black opacity-50"></div>
          </div>
          <div class="absolute inset-0 flex items-end rounded-lg p-6">
            <div>
              <p aria-hidden="true" class="text-sm text-white">Pogledaj kolekciju</p>
              <h3 class="mt-1 font-semibold text-white">
                <a href="#">
                  <span class="absolute inset-0"></span>
                  Muški parfemi
                </a>
              </h3>
            </div>
          </div>
        </div>
        <div class="group relative h-96 rounded-lg bg-white shadow-xl sm:aspect-4/5 sm:h-auto">
          <div aria-hidden="true" class="absolute inset-0 overflow-hidden rounded-lg">
            <div class="absolute inset-0 overflow-hidden group-hover:opacity-75">
              <img 
                src="{{ asset('storage/images/unisexperfume.jpg') }}"
                alt="Person sitting at a wooden desk with paper note organizer, pencil and tablet." 
                class="size-full object-cover" />
            </div>
            <div class="absolute inset-0 bg-linear-to-b from-transparent to-black opacity-50"></div>
          </div>
          <div class="absolute inset-0 flex items-end rounded-lg p-6">
            <div>
              <p aria-hidden="true" class="text-sm text-white">Pogledaj kolekciju</p>
              <h3 class="mt-1 font-semibold text-white">
                <a href="#">
                  <span class="absolute inset-0"></span>
                  Unisex parfemi
                </a>
              </h3>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
  