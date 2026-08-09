<x-filament-panels::page>
    @php
        $topPerf = $topPerfumes ?? [];
        $topCust = $topCustomers ?? [];

        $svgW = 800; $svgH = 200; $padL = 32; $padR = 12; $padT = 20; $padB = 24;
        $cnt = max(1, count($series));
        $stepX = ($svgW - $padL - $padR) / max(1, $cnt - 1);
        $usableH = $svgH - $padT - $padB;

        $points = [];
        foreach ($series as $i => $s) {
            $x = $padL + $stepX * $i;
            $y = $padT + $usableH - (($s['revenue'] / max(1,$maxRev)) * $usableH);
            $points[] = [$x, $y, $s];
        }
        $linePts = implode(' ', array_map(fn($p) => round($p[0],2).','.round($p[1],2), $points));
        $areaPts = $linePts . ' ' . round($padL + $stepX * ($cnt - 1),2) . ','.($svgH - $padB).' ' . $padL . ','.($svgH - $padB);
    @endphp

    <style>
        .ma-wrap { display: flex; flex-direction: column; gap: 1rem; }
        .ma-head { display: flex; flex-direction: column; gap: 0.75rem; padding: 0.9rem 1.1rem; border-radius: 1rem; border: 1px solid rgba(187,161,79,0.22); background: radial-gradient(600px 200px at 100% -50px, rgba(219,197,132,0.16), transparent 60%), linear-gradient(180deg, #fffdf7, #ffffff); }
        .dark .ma-head { background: radial-gradient(600px 200px at 100% -50px, rgba(187,161,79,0.10), transparent 60%), linear-gradient(180deg, #17181b, #131315); border-color: rgba(187,161,79,0.18); }
        .ma-eyebrow { font-size: 0.66rem; letter-spacing: 0.24em; text-transform: uppercase; color: #8b6914; font-weight: 600; }
        .dark .ma-eyebrow { color: #DBC584; }
        .ma-title { font-family: 'Cormorant Garamond', Georgia, serif; font-style: italic; font-weight: 600; font-size: 1.4rem; line-height: 1.15; margin: 0.15rem 0 0.25rem; color: #111827; }
        .dark .ma-title { color: #f3f4f6; }
        .ma-sub { color: #6b7280; font-size: 0.8rem; }
        .dark .ma-sub { color: #9ca3af; }

        .ma-presets { display: flex; flex-wrap: wrap; gap: 0.4rem; }
        .ma-chip { font-size: 0.75rem; font-weight: 600; padding: 0.4rem 0.85rem; border-radius: 999px; border: 1px solid rgba(0,0,0,0.10); background: #fff !important; color: #374151 !important; cursor: pointer; transition: all .15s ease; line-height: 1; }
        .ma-chip:hover { background: #f9fafb !important; color: #111827 !important; }
        .dark .ma-chip { background: #1c1d20 !important; color: #e5e7eb !important; border-color: rgba(255,255,255,0.10); }
        .dark .ma-chip:hover { background: #24252a !important; color: #ffffff !important; }
        .ma-chip--active, .ma-chip--active:hover { background: #111827 !important; background-image: linear-gradient(90deg, #111827, #1f2937) !important; color: #ffffff !important; border-color: transparent !important; box-shadow: 0 0 0 2px #BBA14F, 0 6px 14px -8px rgba(139,105,20,0.5) !important; }
        .dark .ma-chip--active, .dark .ma-chip--active:hover { background: #DBC584 !important; background-image: linear-gradient(90deg, #BBA14F, #DBC584) !important; color: #111827 !important; box-shadow: 0 0 0 2px rgba(255,255,255,0.15), 0 6px 14px -8px rgba(219,197,132,0.35) !important; }

        .ma-kpis { display: grid; grid-template-columns: repeat(1, 1fr); gap: 0.7rem; }
        @media (min-width: 640px) { .ma-kpis { grid-template-columns: repeat(2, 1fr); } }
        @media (min-width: 1024px) { .ma-kpis { grid-template-columns: repeat(4, 1fr); } }
        .ma-kpi { position: relative; overflow: hidden; padding: 0.9rem 1rem; border-radius: 1rem; border: 1px solid rgba(0,0,0,0.06); background: #fff; box-shadow: 0 1px 2px rgba(17,24,39,0.04); }
        .dark .ma-kpi { background: #17181b; border-color: rgba(255,255,255,0.06); }
        .ma-kpi::before { content: ''; position: absolute; inset: 0 0 auto 0; height: 3px; background: linear-gradient(90deg, var(--kc1, #8b6914), var(--kc2, #DBC584)); }
        .ma-kpi-label { font-size: 0.66rem; letter-spacing: 0.14em; text-transform: uppercase; color: #6b7280; font-weight: 600; }
        .dark .ma-kpi-label { color: #9ca3af; }
        .ma-kpi-icon { position: absolute; right: 0.7rem; top: 0.8rem; opacity: 0.32; color: var(--kc1); }
        .ma-kpi-value { margin-top: 0.3rem; font-size: 1.4rem; font-weight: 700; letter-spacing: -0.01em; color: #111827; overflow-wrap: anywhere; }
        .dark .ma-kpi-value { color: #f3f4f6; }
        .ma-kpi-delta { margin-top: 0.35rem; display: inline-flex; align-items: center; gap: 4px; font-size: 0.68rem; font-weight: 600; padding: 0.16rem 0.5rem; border-radius: 999px; }
        .ma-kpi-delta--up   { color: #065f46; background: rgba(16,185,129,0.12); }
        .ma-kpi-delta--down { color: #991b1b; background: rgba(239,68,68,0.12); }
        .ma-kpi-delta--flat { color: #6b7280; background: rgba(107,114,128,0.12); }
        .dark .ma-kpi-delta--up   { color: #6ee7b7; background: rgba(16,185,129,0.14); }
        .dark .ma-kpi-delta--down { color: #fca5a5; background: rgba(239,68,68,0.15); }
        .dark .ma-kpi-delta--flat { color: #d1d5db; background: rgba(255,255,255,0.06); }

        .ma-card { padding: 0.9rem 1rem 0.4rem; border-radius: 1rem; border: 1px solid rgba(0,0,0,0.06); background: #fff; box-shadow: 0 1px 2px rgba(17,24,39,0.04); }
        .dark .ma-card { background: #17181b; border-color: rgba(255,255,255,0.06); }
        .ma-card-hd { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.5rem; margin-bottom: 0.4rem; }
        .ma-card-title { font-weight: 700; font-size: 0.9rem; color: #111827; }
        .dark .ma-card-title { color: #f3f4f6; }
        .ma-card-sub { font-size: 0.72rem; color: #6b7280; }
        .dark .ma-card-sub { color: #9ca3af; }
        .ma-chart-wrap { position: relative; overflow-x: auto; }
        .ma-chart-svg { display: block; width: 100%; height: auto; min-width: 320px; }

        .ma-two { display: grid; grid-template-columns: 1fr; gap: 1rem; }
        @media (min-width: 1024px) { .ma-two { grid-template-columns: 1fr 1fr; } }

        .ma-row { display: flex; align-items: center; gap: 0.7rem; padding: 0.6rem 0; border-bottom: 1px dashed rgba(0,0,0,0.06); }
        .dark .ma-row { border-bottom-color: rgba(255,255,255,0.06); }
        .ma-row:last-child { border-bottom: 0; }
        .ma-rank { flex: 0 0 auto; width: 26px; height: 26px; border-radius: 999px; display: inline-flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.75rem; color: #fff; background: linear-gradient(135deg, #9ca3af, #6b7280); }
        .ma-rank--1 { background: linear-gradient(135deg, #8b6914, #DBC584); box-shadow: 0 4px 10px -4px rgba(139,105,20,0.5); }
        .ma-rank--2 { background: linear-gradient(135deg, #7d7d7d, #c0c0c0); }
        .ma-rank--3 { background: linear-gradient(135deg, #7a4b18, #cd7f32); }
        .ma-name { flex: 1 1 auto; min-width: 0; }
        .ma-name-title { font-weight: 600; color: #111827; font-size: 0.85rem; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .dark .ma-name-title { color: #f3f4f6; }
        .ma-name-sub { font-size: 0.7rem; color: #6b7280; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .dark .ma-name-sub { color: #9ca3af; }
        .ma-metric { flex: 0 0 auto; text-align: right; }
        .ma-metric-val { font-weight: 700; font-size: 0.85rem; color: #111827; }
        .dark .ma-metric-val { color: #f3f4f6; }
        .ma-metric-sub { font-size: 0.68rem; color: #6b7280; }
        .dark .ma-metric-sub { color: #9ca3af; }
        .ma-bar { margin-top: 5px; height: 4px; border-radius: 999px; background: rgba(0,0,0,0.06); overflow: hidden; }
        .dark .ma-bar { background: rgba(255,255,255,0.06); }
        .ma-bar > span { display: block; height: 100%; background: linear-gradient(90deg, #8b6914, #BBA14F, #DBC584); }
        .ma-empty { text-align: center; padding: 1.5rem 0.5rem; color: #6b7280; font-size: 0.8rem; font-style: italic; }
        .dark .ma-empty { color: #9ca3af; }
    </style>

    <div class="ma-wrap" wire:key="ma-{{ $currentRange['from']->timestamp }}-{{ $currentRange['to']->timestamp }}">
        <div class="ma-head">
            <div>
                <div class="ma-eyebrow">Sorénza · Moja Analitika</div>
                <div class="ma-title">Vaša prodaja</div>
                <div class="ma-sub">
                    {{ $currentRange['from']->translatedFormat('d.m.Y') }} — {{ $currentRange['to']->translatedFormat('d.m.Y') }}
                    · <em>{{ $currentRange['label'] }}</em>
                </div>
            </div>
            <div class="ma-presets">
                @foreach($presets as $key => $label)
                    <button type="button" wire:click="setRange('{{ $key }}')" class="ma-chip {{ $this->range === $key ? 'ma-chip--active' : '' }}">
                        {{ $label }}
                    </button>
                @endforeach
            </div>
        </div>

        {{-- KPIs --}}
        <div class="ma-kpis">
            @foreach($kpis as $k)
                @php
                    $delta = $k['delta'];
                    $cls = $delta === null ? 'ma-kpi-delta--flat' : ($delta > 0.5 ? 'ma-kpi-delta--up' : ($delta < -0.5 ? 'ma-kpi-delta--down' : 'ma-kpi-delta--flat'));
                    $palette = match($k['tone']) {
                        'emerald' => ['#059669','#34d399'],
                        'indigo'  => ['#4f46e5','#818cf8'],
                        'rose'    => ['#e11d48','#fda4af'],
                        default   => ['#8b6914','#DBC584'],
                    };
                @endphp
                <div class="ma-kpi" style="--kc1: {{ $palette[0] }}; --kc2: {{ $palette[1] }};">
                    <div class="ma-kpi-icon">@svg($k['icon'], 'w-5 h-5')</div>
                    <div class="ma-kpi-label">{{ $k['label'] }}</div>
                    <div class="ma-kpi-value">{{ $k['value'] }}</div>
                    <div class="ma-kpi-delta {{ $cls }}">
                        @if($delta === null)
                            —
                        @else
                            @if($delta >= 0)
                                <svg xmlns="http://www.w3.org/2000/svg" width="9" height="9" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/></svg>
                                +{{ number_format($delta, 1, ',', '.') }}%
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" width="9" height="9" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                                {{ number_format($delta, 1, ',', '.') }}%
                            @endif
                        @endif
                        <span style="opacity:0.6; font-weight:500; margin-left:2px;">vs prethodni</span>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Chart --}}
        <div class="ma-card">
            <div class="ma-card-hd">
                <div>
                    <div class="ma-card-title">Vaša prodaja (dnevno)</div>
                    <div class="ma-card-sub">Prosjek: <strong>{{ number_format($avgRev, 2, ',', '.') }} KM</strong> · Vrh: <strong>{{ number_format($maxRev, 2, ',', '.') }} KM</strong></div>
                </div>
            </div>
            @if(collect($series)->sum('revenue') <= 0)
                <div class="ma-empty">Još nema Vaših prodaja u ovom periodu.</div>
            @else
                <div class="ma-chart-wrap">
                    <svg class="ma-chart-svg" viewBox="0 0 {{ $svgW }} {{ $svgH }}" preserveAspectRatio="none">
                        @foreach([0.25, 0.5, 0.75, 1.0] as $f)
                            @php $gy = $padT + $usableH - ($usableH * $f); @endphp
                            <line x1="{{ $padL }}" y1="{{ $gy }}" x2="{{ $svgW - $padR }}" y2="{{ $gy }}" stroke="currentColor" stroke-opacity="0.08" stroke-dasharray="3,4"/>
                        @endforeach
                        <defs>
                            <linearGradient id="ma-grad" x1="0" x2="0" y1="0" y2="1">
                                <stop offset="0%" stop-color="#BBA14F" stop-opacity="0.35"/>
                                <stop offset="100%" stop-color="#BBA14F" stop-opacity="0"/>
                            </linearGradient>
                        </defs>
                        <polygon points="{{ $areaPts }}" fill="url(#ma-grad)"/>
                        <polyline points="{{ $linePts }}" fill="none" stroke="#8b6914" stroke-width="2" stroke-linejoin="round" stroke-linecap="round"/>
                        @foreach($points as $p)
                            <circle cx="{{ round($p[0],2) }}" cy="{{ round($p[1],2) }}" r="3" fill="#fff" stroke="#8b6914" stroke-width="1.6">
                                <title>{{ $p[2]['label'] }} · {{ number_format($p[2]['revenue'], 2, ',', '.') }} KM</title>
                            </circle>
                        @endforeach
                        @php $step = max(1, (int) ceil($cnt / 8)); @endphp
                        @foreach($points as $i => $p)
                            @if($i % $step === 0 || $i === $cnt - 1)
                                <text x="{{ round($p[0],2) }}" y="{{ $svgH - 6 }}" text-anchor="middle" font-size="9" fill="currentColor" fill-opacity="0.55">{{ $p[2]['label'] }}</text>
                            @endif
                        @endforeach
                    </svg>
                </div>
            @endif
        </div>

        {{-- Leaderboards --}}
        <div class="ma-two">
            {{-- Top perfumes --}}
            <div class="ma-card">
                <div class="ma-card-hd">
                    <div>
                        <div class="ma-card-title">Vaši najbolji parfemi</div>
                        <div class="ma-card-sub">Rangirano po prometu</div>
                    </div>
                </div>
                @php $mp = collect($topPerf)->max('revenue') ?: 1; @endphp
                @forelse($topPerf as $i => $p)
                    <div class="ma-row">
                        <div class="ma-rank ma-rank--{{ $i + 1 }}">{{ $i + 1 }}</div>
                        <div class="ma-name">
                            <div class="ma-name-title">{{ $p['name'] }}</div>
                            @if(!empty($p['inspired_by']))
                                <div class="ma-name-sub">Inspirisano od: {{ $p['inspired_by'] }}</div>
                            @endif
                            <div class="ma-bar"><span style="width: {{ min(100, ($p['revenue'] / $mp) * 100) }}%"></span></div>
                        </div>
                        <div class="ma-metric">
                            <div class="ma-metric-val">{{ number_format($p['revenue'], 2, ',', '.') }} KM</div>
                            <div class="ma-metric-sub">{{ $p['units'] }} kom</div>
                        </div>
                    </div>
                @empty
                    <div class="ma-empty">Nema podataka.</div>
                @endforelse
            </div>

            {{-- Top customers --}}
            <div class="ma-card">
                <div class="ma-card-hd">
                    <div>
                        <div class="ma-card-title">Vaši najbolji kupci</div>
                        <div class="ma-card-sub">Kupci koji najviše troše</div>
                    </div>
                </div>
                @php $mc = collect($topCust)->max('revenue') ?: 1; @endphp
                @forelse($topCust as $i => $c)
                    <div class="ma-row">
                        <div class="ma-rank ma-rank--{{ $i + 1 }}">{{ $i + 1 }}</div>
                        <div class="ma-name">
                            <div class="ma-name-title">{{ $c['name'] }}</div>
                            <div class="ma-name-sub">
                                @if($c['city']){{ $c['city'] }} · @endif{{ $c['sales'] }} {{ $c['sales'] == 1 ? 'kupovina' : 'kupovina' }}
                            </div>
                            <div class="ma-bar"><span style="width: {{ min(100, ($c['revenue'] / $mc) * 100) }}%"></span></div>
                        </div>
                        <div class="ma-metric">
                            <div class="ma-metric-val">{{ number_format($c['revenue'], 2, ',', '.') }} KM</div>
                            <div class="ma-metric-sub">{{ $c['units'] }} kom</div>
                        </div>
                    </div>
                @empty
                    <div class="ma-empty">Još nema evidentiranih kupaca (samo ručne prodaje sa vezanim kupcem se broje).</div>
                @endforelse
            </div>
        </div>
    </div>
</x-filament-panels::page>
