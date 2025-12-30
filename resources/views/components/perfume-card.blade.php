<div>
    <div class="relative">
        <a href="{{ route('products.show', $perfume->id) }}">
            <div class="relative h-72 w-full overflow-hidden rounded-lg">
                <img 
                src="{{ Storage::url($perfume->main_image) }}"
                alt="{{ $perfume->name }}" 
                class="size-full object-contain" 
                />
                <div class="absolute inset-x-0 bottom-0 flex h-12 items-end justify-end overflow-hidden rounded-lg p-4">
                    <div aria-hidden="true" class="absolute inset-x-0 bottom-0 h-8 bg-gradient-to-t from-black opacity-50"></div>
                    <a href="/shop" class="relative text-lg font-semibold text-white">Brzi pregled</a>
                </div>
            </div>
        </a>
        <div class="relative mt-4">
            <h3 class="text-sm font-medium text-gray-900">Sorénza {{ ucfirst($perfume->tag) }} {{ $perfume->name }}</h3>
            @if(isset($perfume->tag))
                <p class="mt-1 text-sm text-gray-500">Inspirisano od: {{ ucfirst($perfume->inspired_by) }}</p>
            @endif
        </div>
        <div>
            <p>{{ $perfume->price }} KM</p>
        </div>
    </div>
    <div class="mt-6">
        <button 
            class="bg-amber-500 cursor-pointer px-5 py-2.5 hover:bg-gradient-to-r from-amber-500 via-rose-500 to-purple-600 hover:bg-amber-500 text-white font-medium rounded-full shadow-md hover:shadow-lg hover:scale-105 transition-all duration-300 text-sm"
        >
            Dodaj u korpu
            <span class="sr-only">, {{ $perfume->name }}</span>
        </button>
    </div>
</div>
