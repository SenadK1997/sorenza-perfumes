<div class="relative py-16 sm:py-24">
    <!-- Section header -->
    <div class="relative text-center mb-12 px-4">
        <span class="inline-block text-sm font-medium tracking-[0.3em] uppercase text-amber-600 mb-3">Kolekcije</span>
        <h2 class="text-3xl sm:text-4xl font-bold text-gray-800 mb-4">Pronađi Svoj Potpis</h2>
        <div class="w-24 h-1 bg-gradient-to-r from-pink-500 via-purple-500 to-indigo-500 mx-auto rounded-full"></div>
    </div>

    <section aria-labelledby="collection-heading" class="relative">
        <h2 id="collection-heading" class="sr-only">Collections</h2>
        <div class="mx-auto grid max-w-md grid-cols-1 gap-8 px-4 sm:max-w-7xl sm:grid-cols-3 sm:gap-6 sm:px-6 lg:gap-8 lg:px-8">

            <!-- Women's Perfumes Card -->
            <div class="group relative h-[28rem] rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 ease-out overflow-hidden transform hover:-translate-y-2 ring-1 ring-black/10">
                <div aria-hidden="true" class="absolute inset-0 overflow-hidden rounded-2xl">
                    <div class="absolute inset-0 overflow-hidden transition-transform duration-700 ease-out group-hover:scale-110">
                        <img
                          src="{{ asset('storage/images/womanperfume.webp') }}"
                          alt="Ženski parfem"
                          class="size-full object-cover" 
                        />
                    </div>
                    <!-- Gradient overlay -->
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-60 group-hover:opacity-70 transition-opacity duration-500"></div>
                    <div class="absolute inset-0 bg-gradient-to-br from-pink-600/20 to-purple-600/20 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                </div>

                <!-- Decorative corner accent -->
                <div class="absolute top-4 right-4 w-12 h-12 border-t-2 border-r-2 border-white/30 rounded-tr-lg opacity-0 group-hover:opacity-100 transition-all duration-500 transform translate-x-2 -translate-y-2 group-hover:translate-x-0 group-hover:translate-y-0"></div>
                <div class="absolute bottom-4 left-4 w-12 h-12 border-b-2 border-l-2 border-white/30 rounded-bl-lg opacity-0 group-hover:opacity-100 transition-all duration-500 transform -translate-x-2 translate-y-2 group-hover:translate-x-0 group-hover:translate-y-0"></div>

                <div class="absolute inset-0 flex flex-col justify-end rounded-2xl p-8">
                    <div class="transform transition-all duration-500 group-hover:translate-y-0 translate-y-2">
                        <p aria-hidden="true" class="text-sm text-white/80 font-light tracking-wider uppercase mb-2 opacity-0 group-hover:opacity-100 transition-all duration-500 delay-100">Pogledaj kolekciju</p>
                        <h3 class="text-2xl font-bold text-white mb-3">
                            <a href="{{ route('shop', ['gender' => 'female']) }}" class="hover:text-pink-200 transition-colors duration-300">
                                <span class="absolute inset-0"></span>
                                Ženski parfemi
                            </a>
                        </h3>
                        <div class="w-0 group-hover:w-16 h-0.5 bg-gradient-to-r from-pink-400 to-purple-400 transition-all duration-500 ease-out"></div>
                    </div>
                </div>
            </div>

            <!-- Men's Perfumes Card -->
            <div class="group relative h-[28rem] rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 ease-out overflow-hidden transform hover:-translate-y-2 ring-1 ring-black/10">
                <div aria-hidden="true" class="absolute inset-0 overflow-hidden rounded-2xl">
                    <div class="absolute inset-0 overflow-hidden transition-transform duration-700 ease-out group-hover:scale-110">
                        <img
                            src="{{ asset('storage/images/manperfume.webp') }}"
                            alt="Muški parfem"
                            class="size-full object-cover" />
                    </div>
                    <!-- Gradient overlay -->
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-60 group-hover:opacity-70 transition-opacity duration-500"></div>
                    <div class="absolute inset-0 bg-gradient-to-br from-blue-600/20 to-indigo-600/20 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                </div>

                <!-- Decorative corner accent -->
                <div class="absolute top-4 right-4 w-12 h-12 border-t-2 border-r-2 border-white/30 rounded-tr-lg opacity-0 group-hover:opacity-100 transition-all duration-500 transform translate-x-2 -translate-y-2 group-hover:translate-x-0 group-hover:translate-y-0"></div>
                <div class="absolute bottom-4 left-4 w-12 h-12 border-b-2 border-l-2 border-white/30 rounded-bl-lg opacity-0 group-hover:opacity-100 transition-all duration-500 transform -translate-x-2 translate-y-2 group-hover:translate-x-0 group-hover:translate-y-0"></div>

                <div class="absolute inset-0 flex flex-col justify-end rounded-2xl p-8">
                    <div class="transform transition-all duration-500 group-hover:translate-y-0 translate-y-2">
                        <p aria-hidden="true" class="text-sm text-white/80 font-light tracking-wider uppercase mb-2 opacity-0 group-hover:opacity-100 transition-all duration-500 delay-100">Pogledaj kolekciju</p>
                        <h3 class="text-2xl font-bold text-white mb-3">
                            <a href="{{ route('shop', ['gender' => 'male']) }}" class="hover:text-blue-200 transition-colors duration-300">
                                <span class="absolute inset-0"></span>
                                Muški parfemi
                            </a>
                        </h3>
                        <div class="w-0 group-hover:w-16 h-0.5 bg-gradient-to-r from-blue-400 to-indigo-400 transition-all duration-500 ease-out"></div>
                    </div>
                </div>
            </div>

            <!-- Unisex Perfumes Card -->
            <div class="group relative h-[28rem] rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 ease-out overflow-hidden transform hover:-translate-y-2 ring-1 ring-black/10">
                <div aria-hidden="true" class="absolute inset-0 overflow-hidden rounded-2xl">
                    <div class="absolute inset-0 overflow-hidden transition-transform duration-700 ease-out group-hover:scale-110">
                        <img
                            src="{{ asset('storage/images/unisexperfume.webp') }}"
                            alt="Unisex parfem"
                            class="size-full object-cover" />
                    </div>
                    <!-- Gradient overlay -->
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-60 group-hover:opacity-70 transition-opacity duration-500"></div>
                    <div class="absolute inset-0 bg-gradient-to-br from-amber-600/20 to-orange-600/20 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                </div>

                <!-- Decorative corner accent -->
                <div class="absolute top-4 right-4 w-12 h-12 border-t-2 border-r-2 border-white/30 rounded-tr-lg opacity-0 group-hover:opacity-100 transition-all duration-500 transform translate-x-2 -translate-y-2 group-hover:translate-x-0 group-hover:translate-y-0"></div>
                <div class="absolute bottom-4 left-4 w-12 h-12 border-b-2 border-l-2 border-white/30 rounded-bl-lg opacity-0 group-hover:opacity-100 transition-all duration-500 transform -translate-x-2 translate-y-2 group-hover:translate-x-0 group-hover:translate-y-0"></div>

                <div class="absolute inset-0 flex flex-col justify-end rounded-2xl p-8">
                    <div class="transform transition-all duration-500 group-hover:translate-y-0 translate-y-2">
                        <p aria-hidden="true" class="text-sm text-white/80 font-light tracking-wider uppercase mb-2 opacity-0 group-hover:opacity-100 transition-all duration-500 delay-100">Pogledaj kolekciju</p>
                        <h3 class="text-2xl font-bold text-white mb-3">
                            <a href="{{ route('shop', ['gender' => 'unisex']) }}" class="hover:text-amber-200 transition-colors duration-300">
                                <span class="absolute inset-0"></span>
                                Unisex parfemi
                            </a>
                        </h3>
                        <div class="w-0 group-hover:w-16 h-0.5 bg-gradient-to-r from-amber-400 to-orange-400 transition-all duration-500 ease-out"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
