<div class="min-h-[80vh] px-4 py-10"
     style="background:
        radial-gradient(circle at 10% 10%, rgba(255,153,180,0.28) 0%, transparent 45%),
        radial-gradient(circle at 90% 20%, rgba(180,150,255,0.28) 0%, transparent 45%),
        linear-gradient(135deg, #fdf7fb 0%, #f4f0ff 50%, #eef7fb 100%);
        background-attachment: fixed;">
    <div class="mx-auto max-w-5xl">

        @include('livewire.customer.partials.header', ['customer' => $customer])

        @php $unreadMsgs = \App\Services\Messaging::unreadCountFor($customer); @endphp
        @if($unreadMsgs > 0)
            <a href="{{ route('customer.messages') }}"
               class="mt-6 flex items-center justify-between gap-4 rounded-2xl border border-amber-200 bg-gradient-to-r from-amber-50 via-rose-50 to-amber-50 px-5 py-3.5 shadow-sm hover:shadow-md transition">
                <div class="flex items-center gap-3">
                    <div class="h-9 w-9 rounded-full bg-white text-amber-800 flex items-center justify-center shadow-inner">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.4-4 8-9 8-1.4 0-2.7-.3-3.9-.8L3 20l1.3-4a7.8 7.8 0 0 1-1.3-4c0-4.4 4-8 9-8s9 3.6 9 8z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-sm font-semibold text-gray-900">Imate {{ $unreadMsgs }} {{ $unreadMsgs === 1 ? 'novu poruku' : 'nove poruke' }} od Sorenze</div>
                        <div class="text-[11px] text-gray-500">Kliknite da otvorite razgovor.</div>
                    </div>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-amber-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        @endif

        {{-- Loyalty tier hero --}}
        <div class="mt-8 rounded-3xl bg-white/80 backdrop-blur border border-white/90 shadow-xl overflow-hidden">
            <div class="h-1.5 w-full" style="background: linear-gradient(90deg, {{ $tier['accent'] }}, #fff2, {{ $tier['accent'] }});"></div>
            <div class="p-6 sm:p-8 flex flex-col md:flex-row md:items-center md:justify-between gap-5">
                <div class="flex items-center gap-4">
                    <div class="h-14 w-14 rounded-2xl flex items-center justify-center text-white shadow-lg"
                         style="background: linear-gradient(135deg, {{ $tier['accent'] }}, rgba(255,255,255,0.35));">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2l2.4 5 5.6.8-4 3.9.9 5.6L12 14.9 7.1 17.3l.9-5.6-4-3.9 5.6-.8L12 2z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-[10px] uppercase tracking-[0.3em] text-gray-400">Vaš nivo ove godine</div>
                        <div class="mt-1 flex items-center gap-2 flex-wrap">
                            <span class="text-2xl font-serif italic font-semibold" style="color: {{ $tier['accent'] }};">
                                {{ $tier['name'] }}
                            </span>
                            <span class="inline-flex items-center rounded-full text-white text-[10px] font-bold uppercase tracking-widest px-2 py-0.5"
                                  style="background: {{ $tier['accent'] }};">
                                −{{ (int) $tier['discount'] }}% na svaku kupovinu
                            </span>
                        </div>
                        <div class="text-xs text-gray-500 tabular-nums mt-1 space-y-0.5">
                            <div>Potrošeno ove godine: <strong>{{ number_format($realSpentThisYear, 2) }} KM</strong></div>
                            @if($bonusPoints != 0)
                                <div>Poena: <strong class="text-violet-700">{{ number_format($bonusPoints, 0) }}</strong></div>
                            @endif
                            <div class="text-gray-400">Ukupno svih vremena: {{ number_format($totalSpent, 2) }} KM</div>
                        </div>
                    </div>
                </div>

                <div class="flex-1 md:max-w-sm w-full">
                    @if($tier['next'])
                        <div class="flex items-baseline justify-between text-xs text-gray-500 mb-1.5">
                            <span>{{ number_format($tier['min'], 0) }} KM</span>
                            <span class="text-gray-700">
                                Još <strong class="tabular-nums">{{ number_format($tier['to_next'], 2) }} KM</strong> do sljedećeg nivoa
                            </span>
                            <span>{{ number_format($tier['next'], 0) }} KM</span>
                        </div>
                        <div class="h-2 w-full bg-gray-100 rounded-full overflow-hidden">
                            <div class="h-full rounded-full transition-all duration-700"
                                 style="width: {{ $tier['progress_pct'] }}%; background: linear-gradient(90deg, {{ $tier['accent'] }}, {{ $tier['accent'] }}cc);"></div>
                        </div>
                    @else
                        <div class="rounded-2xl bg-gradient-to-r from-violet-50 to-fuchsia-50 border border-violet-200 px-4 py-2.5 text-xs text-violet-800 font-semibold uppercase tracking-widest text-center">
                            ✦ Najviši nivo dostignut ✦
                        </div>
                    @endif
                    <p class="mt-2 text-[10px] uppercase tracking-widest text-gray-400 text-center">
                        Nivo se resetuje {{ $tier['resets_at']->format('d.m.Y') }} u ponoć
                    </p>
                </div>
            </div>

            {{-- Benefits table (all 5 tiers) --}}
            <div class="px-6 sm:px-8 pb-6">
                <div class="text-[10px] uppercase tracking-[0.3em] text-gray-400 mb-3">Svi nivoi i pogodnosti</div>
                <div class="grid grid-cols-5 gap-2">
                    @foreach($allTiers as $t)
                        @php $isCurrent = $t['key'] === $tier['key']; @endphp
                        <div class="rounded-xl border p-3 text-center transition
                                    {{ $isCurrent ? 'shadow-lg scale-[1.03]' : 'bg-white/60 border-gray-200 opacity-80' }}"
                             @if($isCurrent) style="border-color: {{ $t['accent'] }}; background: {{ $t['accent'] }}12;" @endif>
                            <div class="text-[10px] font-semibold uppercase tracking-widest" style="color: {{ $t['accent'] }};">
                                {{ $t['name'] }}
                            </div>
                            <div class="text-xs text-gray-500 mt-1 tabular-nums">
                                @if($t['next'])
                                    {{ number_format($t['min'], 0) }}–{{ number_format($t['next'] - 1, 0) }} KM
                                @else
                                    {{ number_format($t['min'], 0) }}+ KM
                                @endif
                            </div>
                            <div class="mt-2 inline-flex items-center rounded-full text-white text-[10px] font-bold px-2 py-0.5"
                                 style="background: {{ $t['accent'] }};">
                                −{{ (int) $t['discount'] }}%
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Stat cards --}}
        <div class="mt-6 grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4">
            <a href="{{ route('customer.orders', ['tab' => 'active']) }}"
               class="rounded-2xl bg-white/80 backdrop-blur border border-white/90 p-5 shadow-sm hover:shadow-md transition">
                <div class="text-[10px] uppercase tracking-[0.3em] text-gray-400">Aktivne</div>
                <div class="mt-2 text-3xl font-semibold text-amber-800 tabular-nums">{{ $counts['active'] }}</div>
                <div class="text-[11px] text-gray-500 mt-1">u obradi</div>
            </a>
            <a href="{{ route('customer.orders', ['tab' => 'completed']) }}"
               class="rounded-2xl bg-white/80 backdrop-blur border border-white/90 p-5 shadow-sm hover:shadow-md transition">
                <div class="text-[10px] uppercase tracking-[0.3em] text-gray-400">Završene</div>
                <div class="mt-2 text-3xl font-semibold text-emerald-700 tabular-nums">{{ $counts['completed'] }}</div>
                <div class="text-[11px] text-gray-500 mt-1">isporučene</div>
            </a>
            <a href="{{ route('customer.orders', ['tab' => 'cancelled']) }}"
               class="rounded-2xl bg-white/80 backdrop-blur border border-white/90 p-5 shadow-sm hover:shadow-md transition">
                <div class="text-[10px] uppercase tracking-[0.3em] text-gray-400">Otkazane</div>
                <div class="mt-2 text-3xl font-semibold text-red-600 tabular-nums">{{ $counts['cancelled'] }}</div>
                <div class="text-[11px] text-gray-500 mt-1">poništene</div>
            </a>
            <div class="rounded-2xl bg-white/80 backdrop-blur border border-white/90 p-5 shadow-sm">
                <div class="text-[10px] uppercase tracking-[0.3em] text-gray-400">Ukupno potrošeno</div>
                <div class="mt-2 text-2xl font-semibold bg-gradient-to-r from-[#8b6914] to-[#BBA14F] bg-clip-text text-transparent tabular-nums">
                    {{ number_format($totalSpent, 2) }} KM
                </div>
                <div class="text-[11px] text-gray-500 mt-1">životni iznos</div>
            </div>
        </div>

        {{-- Recent orders --}}
        <div class="mt-8 rounded-3xl bg-white/80 backdrop-blur border border-white/90 shadow-xl p-6 sm:p-8">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-serif italic text-2xl text-gray-900">Zadnje narudžbe</h2>
                <a href="{{ route('customer.orders') }}" class="text-xs uppercase tracking-[0.24em] text-amber-800 hover:text-amber-700">Sve</a>
            </div>

            @if($orders->isEmpty())
                <p class="text-sm text-gray-500">Još nemate narudžbi.</p>
            @else
                <div class="divide-y divide-amber-100/70">
                    @foreach($orders as $order)
                        <a href="{{ route('order.track', $order->pretty_id) }}"
                           class="flex items-center justify-between py-3 hover:bg-amber-50/50 -mx-2 px-2 rounded-lg transition">
                            <div>
                                <div class="text-sm font-semibold text-gray-900">#{{ $order->pretty_id }}</div>
                                <div class="text-[11px] text-gray-500">{{ $order->created_at->format('d.m.Y') }}</div>
                            </div>
                            <div class="text-right">
                                <div class="text-sm font-semibold tabular-nums text-gray-900">{{ number_format($order->amount, 2) }} KM</div>
                                <div class="text-[10px] uppercase tracking-widest {{ $order->status->value === 'cancelled' ? 'text-red-600' : ($order->status->value === 'completed' ? 'text-emerald-600' : 'text-amber-700') }}">
                                    {{ $order->status->translatedLabel() }}
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>

    </div>
</div>
