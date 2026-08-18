@php
    $current = request()->route()->getName();
    $unreadMessages = \App\Services\Messaging::unreadCountFor($customer);
    $links = [
        'customer.dashboard' => ['label' => 'Pregled',      'icon' => 'M3 12l9-9 9 9M5 10v10h14V10'],
        'customer.orders'    => ['label' => 'Narudžbe',     'icon' => 'M9 5h6M9 3v18M15 3v18M3 8h18M3 16h18'],
        'customer.messages'  => ['label' => 'Poruke',       'icon' => 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.4-4 8-9 8-1.4 0-2.7-.3-3.9-.8L3 20l1.3-4a7.8 7.8 0 0 1-1.3-4c0-4.4 4-8 9-8s9 3.6 9 8z', 'badge' => $unreadMessages],
        'customer.scent'     => ['label' => 'Profil mirisa','icon' => 'M12 3v3m0 12v3M3 12h3m12 0h3M5.6 5.6l2.1 2.1m8.6 8.6l2.1 2.1M5.6 18.4l2.1-2.1m8.6-8.6l2.1-2.1'],
        'wishlist'           => ['label' => 'Lista želja',  'icon' => 'M4.318 6.318a4.5 4.5 0 0 1 6.364 0L12 7.636l1.318-1.318a4.5 4.5 0 1 1 6.364 6.364L12 20.364l-7.682-7.682a4.5 4.5 0 0 1 0-6.364z', 'external' => true],
        'customer.address'   => ['label' => 'Adresa',       'icon' => 'M12 21s-8-6.5-8-12a8 8 0 1 1 16 0c0 5.5-8 12-8 12z M12 11a2 2 0 100-4 2 2 0 000 4z'],
        'customer.password'  => ['label' => 'Lozinka',      'icon' => 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z M8 11V7a4 4 0 118 0v4'],
    ];
@endphp

<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <div class="text-[10px] tracking-[0.45em] uppercase text-amber-700 font-light">Sorénza · Moj nalog</div>
        <h1 class="mt-1 font-serif italic font-light text-3xl text-gray-900">
            Dobro došli{{ $customer->full_name ? ', ' . explode(' ', $customer->full_name)[0] : '' }}
        </h1>
        <div class="mt-1 text-xs text-gray-500">{{ $customer->email }}</div>
    </div>

    <form method="POST" action="{{ route('customer.logout') }}">
        @csrf
        <button type="submit"
                class="inline-flex items-center gap-2 rounded-full border border-gray-300 bg-white/80 px-4 py-2 text-xs uppercase tracking-[0.2em] text-gray-700 hover:bg-red-50 hover:border-red-200 hover:text-red-700 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
            </svg>
            Odjava
        </button>
    </form>
</div>

<nav class="mt-6 flex flex-wrap gap-2">
    @foreach($links as $route => $meta)
        @php
            $active = $current === $route;
            $badge  = $meta['badge'] ?? null;
        @endphp
        <a href="{{ route($route) }}"
           class="relative inline-flex items-center gap-2 rounded-full px-4 py-2 text-xs uppercase tracking-[0.2em] font-medium transition
                  {{ $active ? 'bg-gradient-to-r from-[#8b6914] via-[#BBA14F] to-[#DBC584] text-white shadow' : 'bg-white/80 text-gray-700 border border-white/90 hover:bg-white' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7">
                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $meta['icon'] }}"/>
            </svg>
            {{ $meta['label'] }}
            @if($badge)
                <span class="inline-flex items-center justify-center min-w-[1.25rem] h-5 rounded-full bg-red-600 text-white text-[10px] font-bold px-1.5">
                    {{ $badge > 9 ? '9+' : $badge }}
                </span>
            @endif
        </a>
    @endforeach
</nav>
