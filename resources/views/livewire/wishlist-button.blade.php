@php
    $size = match($size) {
        'lg' => ['btn' => 'h-11 w-11', 'ico' => 'h-6 w-6'],
        'sm' => ['btn' => 'h-8 w-8',   'ico' => 'h-4 w-4'],
        default => ['btn' => 'h-9 w-9', 'ico' => 'h-5 w-5'],
    };
@endphp
<button
    type="button"
    wire:click.stop="toggle"
    aria-label="{{ $inWishlist ? 'Ukloni sa liste želja' : 'Dodaj na listu želja' }}"
    aria-pressed="{{ $inWishlist ? 'true' : 'false' }}"
    class="wl-btn {{ $size['btn'] }} inline-flex items-center justify-center rounded-full bg-white/90 backdrop-blur shadow-sm ring-1 ring-black/5 hover:scale-110 hover:shadow-md transition-all {{ $inWishlist ? 'wl-btn--on' : '' }}"
>
    <svg xmlns="http://www.w3.org/2000/svg" class="{{ $size['ico'] }} transition-colors"
         viewBox="0 0 24 24"
         fill="{{ $inWishlist ? '#e11d48' : 'none' }}"
         stroke="{{ $inWishlist ? '#e11d48' : 'currentColor' }}"
         stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
        <path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/>
    </svg>
    <style>
        .wl-btn--on { animation: wl-pop .28s ease-out; }
        @keyframes wl-pop { 0%{transform:scale(1)} 50%{transform:scale(1.25)} 100%{transform:scale(1)} }
    </style>
</button>
