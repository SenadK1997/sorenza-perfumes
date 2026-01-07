<a href="/cart" class="group relative p-2 transition-all duration-300" aria-label="Vaša korpa">
    @if($count > 0)
        <span class="absolute top-0 right-0 bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full shadow-sm group-hover:scale-110 transition-transform">
            {{ $count }}
        </span>
    @endif
    
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-7 h-7 text-gray-700 group-hover:text-[#DAAA57] transition-colors">
        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.112 11.213a.45.45 0 0 1-.447.494H4.232a.45.45 0 0 1-.447-.494l1.112-11.213a4.5 4.5 0 0 1 4.474-3.998h4.402a4.5 4.5 0 0 1 4.474 3.998Z" />
    </svg>
</a>