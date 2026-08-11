<x-filament-panels::page>
    <style>
        .ss-wrap { display:flex; flex-direction:column; gap:1rem; }
        .ss-summary { display:grid; grid-template-columns:repeat(1,1fr); gap:0.75rem; }
        @media(min-width:640px){.ss-summary{grid-template-columns:repeat(2,1fr);}}
        @media(min-width:1024px){.ss-summary{grid-template-columns:repeat(4,1fr);}}
        .ss-stat { position:relative; padding:0.85rem 1rem; border-radius:1rem; border:1px solid rgba(187,161,79,0.22); background:#fff; overflow:hidden; }
        .dark .ss-stat { background:#17181b; border-color:rgba(187,161,79,0.18); }
        .ss-stat::before { content:''; position:absolute; inset:0 0 auto 0; height:3px; background:linear-gradient(90deg,#8b6914,#DBC584); }
        .ss-stat-label { font-size:0.68rem; letter-spacing:0.16em; text-transform:uppercase; color:#8b6914; font-weight:700; }
        .dark .ss-stat-label { color:#DBC584; }
        .ss-stat-val { margin-top:2px; font-size:1.35rem; font-weight:700; color:#111827; }
        .dark .ss-stat-val { color:#f3f4f6; }
        .ss-stat-sub { font-size:0.72rem; color:#6b7280; }
        .dark .ss-stat-sub { color:#9ca3af; }

        .ss-search { padding:0.5rem 0.85rem; border-radius:999px; border:1px solid rgba(0,0,0,0.10); background:#fff; color:#111827; width:100%; max-width:420px; font-size:0.85rem; }
        .dark .ss-search { background:#1c1d20; color:#f3f4f6; border-color:rgba(255,255,255,0.10); }

        .ss-list { display:flex; flex-direction:column; gap:0.6rem; }
        .ss-row { border-radius:1rem; border:1px solid rgba(0,0,0,0.06); background:#fff; overflow:hidden; }
        .dark .ss-row { background:#17181b; border-color:rgba(255,255,255,0.06); }
        .ss-row-head { display:flex; align-items:center; gap:0.75rem; padding:0.85rem 1rem; cursor:pointer; transition:background .12s ease; }
        .ss-row-head:hover { background:rgba(219,197,132,0.06); }
        .dark .ss-row-head:hover { background:rgba(219,197,132,0.05); }
        .ss-avatar { flex:0 0 auto; width:36px; height:36px; border-radius:999px; background:linear-gradient(135deg,#8b6914,#DBC584); color:#fff; display:inline-flex; align-items:center; justify-content:center; font-weight:800; font-size:0.9rem; }
        .ss-name { flex:1 1 auto; min-width:0; }
        .ss-name-title { font-weight:700; color:#111827; font-size:0.95rem; }
        .dark .ss-name-title { color:#f3f4f6; }
        .ss-name-sub { font-size:0.72rem; color:#6b7280; }
        .dark .ss-name-sub { color:#9ca3af; }
        .ss-metrics { display:grid; grid-template-columns:repeat(4,minmax(60px,auto)); gap:14px; flex:0 0 auto; text-align:right; align-items:center; }
        .ss-metric { min-width:60px; }
        .ss-metric-val { font-weight:700; color:#111827; font-size:0.9rem; }
        .dark .ss-metric-val { color:#f3f4f6; }
        .ss-metric-label { font-size:0.62rem; letter-spacing:0.12em; text-transform:uppercase; color:#6b7280; }
        .dark .ss-metric-label { color:#9ca3af; }
        .ss-chevron { transition:transform .18s ease; color:#6b7280; }
        .ss-chevron.is-open { transform:rotate(180deg); color:#8b6914; }
        .dark .ss-chevron.is-open { color:#DBC584; }

        .ss-body { padding:0.25rem 1rem 0.85rem; border-top:1px dashed rgba(0,0,0,0.06); background:linear-gradient(180deg, rgba(250,247,240,0.4), transparent); }
        .dark .ss-body { border-top-color:rgba(255,255,255,0.06); background:linear-gradient(180deg, rgba(24,20,15,0.4), transparent); }
        .ss-body-title { font-size:0.72rem; letter-spacing:0.14em; text-transform:uppercase; color:#8b6914; font-weight:700; padding:10px 0 6px; }
        .dark .ss-body-title { color:#DBC584; }
        .ss-perf-list { display:grid; grid-template-columns:1fr; gap:6px; }
        @media(min-width:768px){.ss-perf-list{grid-template-columns:1fr 1fr;}}
        .ss-perf { display:flex; align-items:center; gap:8px; padding:6px 10px; border-radius:0.55rem; background:rgba(0,0,0,0.02); font-size:0.82rem; }
        .dark .ss-perf { background:rgba(255,255,255,0.03); }
        .ss-perf-name { flex:1 1 auto; min-width:0; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; color:#111827; font-weight:500; }
        .dark .ss-perf-name { color:#e5e7eb; }
        .ss-badge { flex:0 0 auto; font-size:0.7rem; font-weight:700; padding:2px 8px; border-radius:999px; }
        .ss-badge--ok { background:#dcfce7; color:#166534; }
        .ss-badge--low { background:#fef3c7; color:#92400e; }
        .ss-badge--crit { background:#fee2e2; color:#991b1b; }
        .dark .ss-badge--ok { background:rgba(34,197,94,0.15); color:#86efac; }
        .dark .ss-badge--low { background:rgba(245,158,11,0.15); color:#fcd34d; }
        .dark .ss-badge--crit { background:rgba(239,68,68,0.15); color:#fca5a5; }

        .ss-empty { padding:2rem 1rem; text-align:center; color:#6b7280; font-style:italic; font-size:0.85rem; }
        .dark .ss-empty { color:#9ca3af; }

        @media(max-width:640px){
            .ss-metrics { grid-template-columns:repeat(2,1fr); }
            .ss-metric-label { font-size:0.58rem; }
        }
    </style>

    <div class="ss-wrap">

        {{-- Summary --}}
        <div class="ss-summary">
            <div class="ss-stat">
                <div class="ss-stat-label">Prodavača</div>
                <div class="ss-stat-val">{{ $totals['sellers'] }}</div>
                <div class="ss-stat-sub">sa lagerom</div>
            </div>
            <div class="ss-stat">
                <div class="ss-stat-label">Različitih parfema</div>
                <div class="ss-stat-val">{{ $totals['unique_perfumes'] }}</div>
                <div class="ss-stat-sub">ukupno svih prodavača</div>
            </div>
            <div class="ss-stat">
                <div class="ss-stat-label">Komada u lageru</div>
                <div class="ss-stat-val">{{ number_format($totals['total_units'], 0, ',', '.') }}</div>
                <div class="ss-stat-sub">svi flakoni zbrojeno</div>
            </div>
            <div class="ss-stat">
                <div class="ss-stat-label">Vrijednost</div>
                <div class="ss-stat-val">{{ number_format($totals['inventory_value'], 2, ',', '.') }} KM</div>
                <div class="ss-stat-sub">nabavna vrijednost</div>
            </div>
        </div>

        {{-- Search --}}
        <div>
            <input type="search" wire:model.live.debounce.300ms="search" placeholder="Pretraži prodavača..." class="ss-search"/>
        </div>

        {{-- Sellers list --}}
        <div class="ss-list">
            @forelse($sellers as $s)
                <div class="ss-row">
                    <div class="ss-row-head" wire:click="toggle({{ $s->id }})">
                        <div class="ss-avatar">{{ mb_substr($s->name, 0, 1) }}</div>
                        <div class="ss-name">
                            <div class="ss-name-title">{{ $s->name }}</div>
                            <div class="ss-name-sub">{{ $s->email }}</div>
                        </div>
                        <div class="ss-metrics">
                            <div class="ss-metric">
                                <div class="ss-metric-val">{{ $s->unique_in_stock }}/{{ $s->catalog_total }}</div>
                                <div class="ss-metric-label">Različitih</div>
                            </div>
                            <div class="ss-metric">
                                <div class="ss-metric-val">{{ number_format($s->total_units, 0, ',', '.') }}</div>
                                <div class="ss-metric-label">Komada</div>
                            </div>
                            <div class="ss-metric">
                                <div class="ss-metric-val">{{ number_format($s->inventory_value, 2, ',', '.') }} KM</div>
                                <div class="ss-metric-label">Vrijednost</div>
                            </div>
                            <div class="ss-metric">
                                <svg xmlns="http://www.w3.org/2000/svg" class="ss-chevron {{ $expandedId === $s->id ? 'is-open' : '' }}" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    @if($expandedId === $s->id)
                        <div class="ss-body">
                            {{-- HAS --}}
                            <div class="ss-body-title" style="color:#166534;">
                                ✓ NA STANJU — {{ $expandedRows->count() }} parfema
                            </div>
                            @if($expandedRows->count() === 0)
                                <div class="ss-empty" style="padding:1rem;">Nema stavki sa stanjem > 0.</div>
                            @else
                                <div class="ss-perf-list">
                                    @foreach($expandedRows as $p)
                                        @php
                                            $cls = $p->pivot_stock <= 2 ? 'ss-badge--crit' : ($p->pivot_stock <= 5 ? 'ss-badge--low' : 'ss-badge--ok');
                                        @endphp
                                        <div class="ss-perf">
                                            <div class="ss-perf-name">
                                                {{ $p->name }}
                                                @if($p->inspired_by)
                                                    <span style="color:#6b7280; font-weight:400; font-style:italic;"> — {{ $p->inspired_by }}</span>
                                                @endif
                                            </div>
                                            <span class="ss-badge {{ $cls }}">{{ $p->pivot_stock }} kom</span>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            {{-- MISSING --}}
                            <div class="ss-body-title" style="color:#991b1b; margin-top:1rem;">
                                ✕ NEDOSTAJE — {{ $missingRows->count() }} parfema
                            </div>
                            @if($missingRows->count() === 0)
                                <div class="ss-empty" style="padding:1rem;">Prodavač ima sve aktivne parfeme na lageru 🎉</div>
                            @else
                                <div class="ss-perf-list">
                                    @foreach($missingRows as $p)
                                        <div class="ss-perf" style="opacity:0.85;">
                                            <div class="ss-perf-name">
                                                {{ $p->name }}
                                                @if($p->inspired_by)
                                                    <span style="color:#6b7280; font-weight:400; font-style:italic;"> — {{ $p->inspired_by }}</span>
                                                @endif
                                            </div>
                                            <span class="ss-badge ss-badge--crit">nema</span>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            @empty
                <div class="ss-empty">Nema prodavača sa lagerom.</div>
            @endforelse
        </div>
    </div>
</x-filament-panels::page>
