<div class="min-h-[80vh] px-4 py-10"
     style="background:
        radial-gradient(circle at 10% 10%, rgba(255,153,180,0.28) 0%, transparent 45%),
        radial-gradient(circle at 90% 20%, rgba(180,150,255,0.28) 0%, transparent 45%),
        linear-gradient(135deg, #fdf7fb 0%, #f4f0ff 50%, #eef7fb 100%);
        background-attachment: fixed;">
    <div class="mx-auto max-w-5xl">
        @include('livewire.customer.partials.header', ['customer' => $customer])

        @if(session('status'))
            <div class="mt-6 rounded-2xl border border-emerald-200 bg-emerald-50/70 px-4 py-3 text-sm text-emerald-800">
                {{ session('status') }}
            </div>
        @endif

        {{-- Tabs --}}
        @php
            $tabs = [
                'all'       => ['label' => 'Sve',       'color' => 'from-gray-700 to-gray-900',       'text' => 'text-gray-700'],
                'active'    => ['label' => 'Aktivne',   'color' => 'from-amber-500 to-orange-500',    'text' => 'text-amber-800'],
                'completed' => ['label' => 'Završene',  'color' => 'from-emerald-500 to-teal-500',    'text' => 'text-emerald-700'],
                'cancelled' => ['label' => 'Otkazane',  'color' => 'from-rose-500 to-red-600',        'text' => 'text-red-700'],
            ];
        @endphp

        <div class="mt-8 flex flex-wrap gap-2">
            @foreach($tabs as $key => $meta)
                @php $isActive = $tab === $key; @endphp
                <button type="button" wire:click="setTab('{{ $key }}')"
                        class="inline-flex items-center gap-2 rounded-full px-4 py-2 text-xs uppercase tracking-[0.2em] font-medium transition
                               {{ $isActive ? 'bg-gradient-to-r ' . $meta['color'] . ' text-white shadow' : 'bg-white/80 border border-white/90 ' . $meta['text'] . ' hover:bg-white' }}">
                    {{ $meta['label'] }}
                    <span class="inline-flex items-center justify-center min-w-[1.5rem] rounded-full text-[10px] font-bold px-1.5 py-0.5
                                 {{ $isActive ? 'bg-white/25 text-white' : 'bg-gray-100 text-gray-600' }}">
                        {{ $counts[$key] }}
                    </span>
                </button>
            @endforeach
        </div>

        <div class="mt-6 rounded-3xl bg-white/80 backdrop-blur border border-white/90 shadow-xl p-6 sm:p-8">
            @if($rows->isEmpty())
                <div class="text-center py-8">
                    <p class="text-sm text-gray-500">Nema kupovina u ovoj kategoriji.</p>
                    <a href="{{ route('shop') }}" class="mt-4 inline-block text-xs uppercase tracking-[0.24em] text-amber-800 hover:text-amber-700">
                        Otkrijte kolekciju →
                    </a>
                </div>
            @else
                <div class="divide-y divide-amber-100/70">
                    @foreach($rows as $row)
                        <div class="py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            {{-- Left: label + meta --}}
                            <div class="min-w-0 flex items-start gap-3">
                                @if($row->kind === 'sale' && !empty($row->perfume?->main_image))
                                    <img src="{{ Storage::url($row->perfume->main_image) }}"
                                         alt="{{ $row->perfume->name }}"
                                         class="h-12 w-12 rounded-lg object-cover border border-white shadow-sm shrink-0">
                                @else
                                    <div class="h-12 w-12 rounded-lg bg-gradient-to-br from-amber-50 to-rose-50 border border-amber-100 flex items-center justify-center text-amber-700 shrink-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5h6M9 3v18M15 3v18M3 8h18M3 16h18"/>
                                        </svg>
                                    </div>
                                @endif

                                <div class="min-w-0">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        @if($row->kind === 'order')
                                            <a href="{{ route('order.track', $row->route_key) }}"
                                               class="text-sm font-semibold text-gray-900 hover:text-amber-800">
                                                {{ $row->label }}
                                            </a>
                                            <span class="inline-flex items-center rounded-full bg-sky-50 text-sky-700 border border-sky-200 text-[9px] font-bold uppercase tracking-widest px-2 py-0.5">
                                                Online
                                            </span>
                                        @else
                                            <span class="text-sm font-semibold text-gray-900 truncate">
                                                {{ $row->label }}
                                            </span>
                                            <span class="inline-flex items-center rounded-full bg-fuchsia-50 text-fuchsia-700 border border-fuchsia-200 text-[9px] font-bold uppercase tracking-widest px-2 py-0.5">
                                                Direktno
                                            </span>
                                        @endif
                                    </div>
                                    <div class="text-[11px] text-gray-500 mt-0.5">
                                        {{ $row->created_at->format('d.m.Y H:i') }}
                                        @if($row->items_count)
                                            · {{ $row->items_count }} {{ $row->items_count === 1 ? 'komad' : 'komada' }}
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- Right: amount + status + actions --}}
                            <div class="flex items-center gap-4">
                                <div class="text-right">
                                    <div class="text-sm font-semibold tabular-nums text-gray-900">
                                        {{ number_format($row->amount, 2) }} KM
                                    </div>
                                    @php
                                        $statusClass = match($row->status_key) {
                                            'completed' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                            'cancelled' => 'bg-red-50 text-red-700 border-red-200',
                                            'taken'     => 'bg-blue-50 text-blue-700 border-blue-200',
                                            default     => 'bg-amber-50 text-amber-800 border-amber-200',
                                        };
                                    @endphp
                                    <span class="inline-block mt-1 rounded-full border px-2 py-0.5 text-[10px] uppercase tracking-widest {{ $statusClass }}">
                                        {{ $row->status_label }}
                                    </span>
                                </div>

                                <div class="flex flex-col gap-1.5">
                                    @if($row->kind === 'order')
                                        <a href="{{ route('order.track', $row->route_key) }}"
                                           class="inline-flex items-center justify-center gap-1 rounded-full border border-gray-300 bg-white px-3 py-1.5 text-[10px] uppercase tracking-[0.2em] text-gray-700 hover:bg-gray-50 transition">
                                            Detalji
                                        </a>
                                        @if($row->status_key === 'completed')
                                            <button type="button"
                                                    wire:click="reorderOrder({{ $row->id }})"
                                                    wire:confirm="Dodati sve artikle iz narudžbe {{ $row->label }} u vašu korpu?"
                                                    class="inline-flex items-center justify-center gap-1 rounded-full bg-gradient-to-r from-[#8b6914] via-[#BBA14F] to-[#DBC584] px-3 py-1.5 text-[10px] uppercase tracking-[0.2em] text-white shadow hover:opacity-95 transition">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v6h6M20 20v-6h-6M4 10a8 8 0 0 1 14.6-4.5M20 14a8 8 0 0 1-14.6 4.5"/>
                                                </svg>
                                                Naruči ponovo
                                            </button>
                                        @endif
                                    @else
                                        @if($row->status_key !== 'cancelled' && $row->perfume)
                                            <a href="{{ route('products.show', $row->perfume->id) }}"
                                               class="inline-flex items-center justify-center gap-1 rounded-full border border-gray-300 bg-white px-3 py-1.5 text-[10px] uppercase tracking-[0.2em] text-gray-700 hover:bg-gray-50 transition">
                                                Pogledaj
                                            </a>
                                            <button type="button"
                                                    wire:click="reorderSale({{ $row->id }})"
                                                    wire:confirm="Dodati {{ $row->perfume->name }} u vašu korpu?"
                                                    class="inline-flex items-center justify-center gap-1 rounded-full bg-gradient-to-r from-[#8b6914] via-[#BBA14F] to-[#DBC584] px-3 py-1.5 text-[10px] uppercase tracking-[0.2em] text-white shadow hover:opacity-95 transition">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v6h6M20 20v-6h-6M4 10a8 8 0 0 1 14.6-4.5M20 14a8 8 0 0 1-14.6 4.5"/>
                                                </svg>
                                                Kupi ponovo
                                            </button>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6">
                    {{ $rows->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
