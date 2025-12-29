<div>
    <div class="relative">
        <div class="relative h-72 w-full overflow-hidden rounded-lg">
            <img 
                src="{{ Storage::url($perfume->main_image) }}"
                alt="{{ $perfume->name }}" 
                class="size-full object-cover" 
            />
        </div>
        <div class="relative mt-4">
            <h3 class="text-sm font-medium text-gray-900">{{ $perfume->name }}</h3>
            @if(isset($perfume->tag))
                <p class="mt-1 text-sm text-gray-500">{{ ucfirst($perfume->tag) }}</p>
            @endif
        </div>
        <div class="absolute inset-x-0 top-0 flex h-72 items-end justify-end overflow-hidden rounded-lg p-4">
            <div aria-hidden="true" class="absolute inset-x-0 bottom-0 h-36 bg-gradient-to-t from-black opacity-50"></div>
            <p class="relative text-lg font-semibold text-white">{{ $perfume->price }} KM</p>
        </div>
    </div>
    <div class="mt-6">
        <a href="#" class="relative flex items-center justify-center rounded-md border border-transparent bg-gray-100 px-8 py-2 text-sm font-medium text-gray-900 hover:bg-gray-200">
            Dodaj u korpu
            <span class="sr-only">, {{ $perfume->name }}</span>
        </a>
    </div>
</div>
