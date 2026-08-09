<x-filament-panels::page>
    @php
        // getViewData() is auto-extracted by Filament into: $currentRange, $previousRange,
        // $kpis, $series, $maxRev, $avgRev, $topPerfumes, $topSellers, $presets.
        // NOTE: we intentionally use $currentRange (not $range) because the page has a
        // public string $range property that would otherwise shadow the array in the view.
        $topPerf = $topPerfumes ?? [];
        $topSell = $topSellers  ?? [];

        $svgW = 800; $svgH = 220; $padL = 32; $padR = 12; $padT = 20; $padB = 26;
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
        .sa-wrap { display: flex; flex-direction: column; gap: 1.25rem; }

        /* Header + preset chips */
        .sa-head {
            display: flex; flex-direction: column; gap: 0.75rem;
            padding: 1rem 1.25rem; border-radius: 1rem;
            border: 1px solid rgba(187,161,79,0.22);
            background:
                radial-gradient(600px 200px at 100% -50px, rgba(219,197,132,0.16), transparent 60%),
                linear-gradient(180deg, #fffdf7, #ffffff);
        }
        .dark .sa-head {
            background:
                radial-gradient(600px 200px at 100% -50px, rgba(187,161,79,0.10), transparent 60%),
                linear-gradient(180deg, #17181b, #131315);
            border-color: rgba(187,161,79,0.18);
        }
        .sa-eyebrow {
            font-size: 0.7rem; letter-spacing: 0.24em; text-transform: uppercase;
            color: #8b6914; font-weight: 600;
        }
        .dark .sa-eyebrow { color: #DBC584; }
        .sa-title {
            font-family: 'Cormorant Garamond', Georgia, serif;
            font-style: italic; font-weight: 600; font-size: 1.6rem;
            line-height: 1.15; margin: 0.15rem 0 0.35rem;
            background: linear-gradient(90deg, #111827, #8b6914 60%, #111827);
            -webkit-background-clip: text; background-clip: text; -webkit-text-fill-color: transparent;
        }
        .dark .sa-title {
            background: linear-gradient(90deg, #f3f4f6, #DBC584 60%, #f3f4f6);
            -webkit-background-clip: text; background-clip: text; -webkit-text-fill-color: transparent;
        }
        .sa-sub { color: #6b7280; font-size: 0.85rem; }
        .dark .sa-sub { color: #9ca3af; }

        .sa-presets { display: flex; flex-wrap: wrap; gap: 0.4rem; }
        .sa-chip {
            font-size: 0.78rem; font-weight: 600; padding: 0.4rem 0.9rem;
            border-radius: 999px; border: 1px solid rgba(0,0,0,0.10);
            background: #fff !important; color: #374151 !important;
            cursor: pointer; transition: all .15s ease;
            line-height: 1;
        }
        .sa-chip:hover { background: #f9fafb !important; color: #111827 !important; }
        .dark .sa-chip {
            background: #1c1d20 !important; color: #e5e7eb !important;
            border-color: rgba(255,255,255,0.10);
        }
        .dark .sa-chip:hover { background: #24252a !important; color: #ffffff !important; }

        /* Active — strong contrast in BOTH themes: dark chip, white text with gold ring */
        .sa-chip--active,
        .sa-chip--active:hover {
            background: #111827 !important;
            background-image: linear-gradient(90deg, #111827, #1f2937) !important;
            color: #ffffff !important;
            border-color: transparent !important;
            box-shadow: 0 0 0 2px #BBA14F, 0 8px 18px -10px rgba(139,105,20,0.55) !important;
        }
        .dark .sa-chip--active,
        .dark .sa-chip--active:hover {
            background: #DBC584 !important;
            background-image: linear-gradient(90deg, #BBA14F, #DBC584) !important;
            color: #111827 !important;
            box-shadow: 0 0 0 2px rgba(255,255,255,0.15), 0 8px 18px -10px rgba(219,197,132,0.35) !important;
        }

        .sa-custom { display: flex; flex-wrap: wrap; gap: 0.5rem; align-items: center; margin-top: 0.5rem; }
        .sa-custom input {
            padding: 0.4rem 0.6rem; border-radius: 0.5rem;
            border: 1px solid rgba(0,0,0,0.10); background: #fff; color: #111827; font-size: 0.85rem;
        }
        .dark .sa-custom input { background: #1c1d20; color: #f3f4f6; border-color: rgba(255,255,255,0.10); }
        .sa-custom label { font-size: 0.75rem; color: #6b7280; }
        .dark .sa-custom label { color: #9ca3af; }

        /* Filter row */
        .sa-filters {
            display: grid;
            grid-template-columns: repeat(1, 1fr);
            gap: 0.6rem;
            padding-top: 0.5rem;
            border-top: 1px dashed rgba(187,161,79,0.30);
            margin-top: 0.4rem;
        }
        @media (min-width: 640px) { .sa-filters { grid-template-columns: repeat(3, 1fr) auto; align-items: end; } }
        .sa-filter { display: flex; flex-direction: column; gap: 4px; min-width: 0; }
        .sa-filter label {
            font-size: 0.65rem; text-transform: uppercase; letter-spacing: 0.16em;
            color: #8b6914; font-weight: 600;
        }
        .dark .sa-filter label { color: #DBC584; }
        .sa-filter select {
            padding: 0.5rem 0.65rem;
            border-radius: 0.55rem;
            border: 1px solid rgba(0,0,0,0.10);
            background: #fff; color: #111827; font-size: 0.85rem;
            width: 100%; min-width: 0;
        }
        .dark .sa-filter select {
            background: #1c1d20; color: #f3f4f6; border-color: rgba(255,255,255,0.10);
        }
        .sa-filter-clear {
            font-size: 0.72rem; font-weight: 600; padding: 0.5rem 0.85rem;
            border-radius: 999px; border: 1px solid rgba(220, 38, 38, 0.3);
            background: rgba(254, 226, 226, 0.4); color: #991b1b;
            cursor: pointer; align-self: end;
            transition: all .15s ease;
        }
        .sa-filter-clear:hover { background: rgba(254, 226, 226, 0.7); }
        .dark .sa-filter-clear { background: rgba(220,38,38,0.15); color: #fca5a5; border-color: rgba(220, 38, 38, 0.35); }

        /* KPI grid */
        .sa-kpis { display: grid; grid-template-columns: repeat(1, 1fr); gap: 0.85rem; }
        @media (min-width: 640px) { .sa-kpis { grid-template-columns: repeat(2, 1fr); } }
        @media (min-width: 1024px) { .sa-kpis { grid-template-columns: repeat(4, 1fr); } }

        .sa-kpi {
            position: relative; overflow: hidden;
            padding: 1.1rem 1.15rem; border-radius: 1rem;
            border: 1px solid rgba(0,0,0,0.06); background: #fff;
            box-shadow: 0 1px 2px rgba(17,24,39,0.04), 0 6px 20px -14px rgba(17,24,39,0.15);
        }
        .dark .sa-kpi { background: #17181b; border-color: rgba(255,255,255,0.06); box-shadow: 0 1px 2px rgba(0,0,0,0.35), 0 8px 22px -14px rgba(0,0,0,0.55); }
        .sa-kpi::before {
            content: ''; position: absolute; inset: 0 0 auto 0; height: 3px;
            background: linear-gradient(90deg, var(--kpi-c1, #8b6914), var(--kpi-c2, #DBC584));
        }
        .sa-kpi-label { font-size: 0.72rem; letter-spacing: 0.14em; text-transform: uppercase; color: #6b7280; font-weight: 600; }
        .dark .sa-kpi-label { color: #9ca3af; }
        .sa-kpi-icon { position: absolute; right: 0.85rem; top: 0.9rem; opacity: 0.35; color: var(--kpi-c1); }
        .sa-kpi-value { margin-top: 0.35rem; font-size: 1.55rem; font-weight: 700; letter-spacing: -0.01em; color: #111827; overflow-wrap: anywhere; }
        .dark .sa-kpi-value { color: #f3f4f6; }
        .sa-kpi-delta { margin-top: 0.4rem; display: inline-flex; align-items: center; gap: 4px; font-size: 0.75rem; font-weight: 600; padding: 0.2rem 0.55rem; border-radius: 999px; }
        .sa-kpi-delta--up   { color: #065f46; background: rgba(16,185,129,0.12); }
        .sa-kpi-delta--down { color: #991b1b; background: rgba(239,68,68,0.12); }
        .sa-kpi-delta--flat { color: #6b7280; background: rgba(107,114,128,0.12); }
        .dark .sa-kpi-delta--up   { color: #6ee7b7; background: rgba(16,185,129,0.14); }
        .dark .sa-kpi-delta--down { color: #fca5a5; background: rgba(239,68,68,0.15); }
        .dark .sa-kpi-delta--flat { color: #d1d5db; background: rgba(255,255,255,0.06); }

        /* Chart card */
        .sa-card {
            padding: 1rem 1.15rem 0.5rem; border-radius: 1rem;
            border: 1px solid rgba(0,0,0,0.06); background: #fff;
            box-shadow: 0 1px 2px rgba(17,24,39,0.04);
        }
        .dark .sa-card { background: #17181b; border-color: rgba(255,255,255,0.06); }
        .sa-card-hd { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.5rem; margin-bottom: 0.5rem; }
        .sa-card-title { font-weight: 700; font-size: 0.95rem; color: #111827; }
        .dark .sa-card-title { color: #f3f4f6; }
        .sa-card-sub { font-size: 0.78rem; color: #6b7280; }
        .dark .sa-card-sub { color: #9ca3af; }

        .sa-chart-wrap { position: relative; overflow-x: auto; }
        .sa-chart-svg { display: block; width: 100%; height: auto; min-width: 320px; }

        /* Two-col grid for leaderboards */
        .sa-two { display: grid; grid-template-columns: 1fr; gap: 1rem; }
        @media (min-width: 1024px) { .sa-two { grid-template-columns: 1fr 1fr; } }

        .sa-row { display: flex; align-items: center; gap: 0.75rem; padding: 0.7rem 0; border-bottom: 1px dashed rgba(0,0,0,0.06); }
        .dark .sa-row { border-bottom-color: rgba(255,255,255,0.06); }
        .sa-row:last-child { border-bottom: 0; }
        .sa-rank {
            flex: 0 0 auto; width: 28px; height: 28px; border-radius: 999px;
            display: inline-flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 0.8rem; color: #fff;
            background: linear-gradient(135deg, #9ca3af, #6b7280);
        }
        .sa-rank--1 { background: linear-gradient(135deg, #8b6914, #DBC584); box-shadow: 0 4px 10px -4px rgba(139,105,20,0.5); }
        .sa-rank--2 { background: linear-gradient(135deg, #7d7d7d, #c0c0c0); }
        .sa-rank--3 { background: linear-gradient(135deg, #7a4b18, #cd7f32); }
        .sa-name { flex: 1 1 auto; min-width: 0; }
        .sa-name-title { font-weight: 600; color: #111827; font-size: 0.9rem; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .dark .sa-name-title { color: #f3f4f6; }
        .sa-name-sub { font-size: 0.75rem; color: #6b7280; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .dark .sa-name-sub { color: #9ca3af; }
        .sa-metric { flex: 0 0 auto; text-align: right; }
        .sa-metric-val { font-weight: 700; font-size: 0.9rem; color: #111827; }
        .dark .sa-metric-val { color: #f3f4f6; }
        .sa-metric-sub { font-size: 0.72rem; color: #6b7280; }
        .dark .sa-metric-sub { color: #9ca3af; }
        .sa-bar { margin-top: 6px; height: 4px; border-radius: 999px; background: rgba(0,0,0,0.06); overflow: hidden; }
        .dark .sa-bar { background: rgba(255,255,255,0.06); }
        .sa-bar > span { display: block; height: 100%; background: linear-gradient(90deg, #8b6914, #BBA14F, #DBC584); }

        .sa-empty {
            text-align: center; padding: 2rem 1rem; color: #6b7280;
            font-size: 0.85rem; font-style: italic;
        }
        .dark .sa-empty { color: #9ca3af; }
    </style>

    <div class="sa-wrap" wire:key="sa-{{ $currentRange['from']->timestamp }}-{{ $currentRange['to']->timestamp }}-{{ $currentRange['label'] }}">

        {{-- Header + presets --}}
        <div class="sa-head">
            <div>
                <div class="sa-eyebrow">Sorénza · Analitika</div>
                <div class="sa-title">Pregled prodaje</div>
                <div class="sa-sub">
                    {{ $currentRange['from']->translatedFormat('d.m.Y') }} — {{ $currentRange['to']->translatedFormat('d.m.Y') }}
                    · <em>{{ $currentRange['label'] }}</em>
                </div>
            </div>

            <div class="sa-presets">
                @foreach($presets as $key => $label)
                    <button type="button"
                            wire:click="setRange('{{ $key }}')"
                            class="sa-chip {{ $this->range === $key ? 'sa-chip--active' : '' }}">
                        {{ $label }}
                    </button>
                @endforeach
            </div>

            @if($this->range === 'custom')
                <div class="sa-custom">
                    <label>Od</label>
                    <input type="date" wire:model.live.debounce.300ms="customFrom" max="{{ now()->toDateString() }}">
                    <label>do</label>
                    <input type="date" wire:model.live.debounce.300ms="customTo" max="{{ now()->toDateString() }}">
                </div>
            @endif

            {{-- Filters row --}}
            <div class="sa-filters">
                <div class="sa-filter">
                    <label>Parfem</label>
                    <select wire:model.live="filterPerfumeId">
                        <option value="">Svi parfemi</option>
                        @foreach($perfumeOptions as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="sa-filter">
                    <label>Prodavač</label>
                    <select wire:model.live="filterSellerId">
                        <option value="">Svi prodavači</option>
                        @foreach($sellerOptions as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="sa-filter">
                    <label>Kanton</label>
                    <select wire:model.live="filterCanton">
                        <option value="">Svi kantoni</option>
                        @foreach($cantonOptions as $val => $label)
                            <option value="{{ $val }}">{{ $val }}</option>
                        @endforeach
                    </select>
                </div>
                @if($anyFilter)
                    <button type="button" wire:click="clearFilters" class="sa-filter-clear">
                        ✕ Očisti filtere
                    </button>
                @endif
            </div>
        </div>

        {{-- KPI tiles --}}
        <div class="sa-kpis">
            @foreach($kpis as $k)
                @php
                    $delta = $k['delta'];
                    $cls = $delta === null ? 'sa-kpi-delta--flat'
                          : ($delta > 0.5 ? 'sa-kpi-delta--up'
                          : ($delta < -0.5 ? 'sa-kpi-delta--down' : 'sa-kpi-delta--flat'));
                    $palette = match($k['tone'] ?? 'primary') {
                        'emerald' => ['#059669','#34d399'],
                        'indigo'  => ['#4f46e5','#818cf8'],
                        'rose'    => ['#e11d48','#fda4af'],
                        default   => ['#8b6914','#DBC584'],
                    };
                @endphp
                <div class="sa-kpi" style="--kpi-c1: {{ $palette[0] }}; --kpi-c2: {{ $palette[1] }};">
                    <div class="sa-kpi-icon">
                        @svg($k['icon'], 'w-6 h-6')
                    </div>
                    <div class="sa-kpi-label">{{ $k['label'] }}</div>
                    <div class="sa-kpi-value">{{ $k['value'] }}</div>
                    <div class="sa-kpi-delta {{ $cls }}">
                        @if($delta === null)
                            —
                        @else
                            @if($delta >= 0)
                                <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/></svg>
                                +{{ number_format($delta, 1, ',', '.') }}%
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                                {{ number_format($delta, 1, ',', '.') }}%
                            @endif
                        @endif
                        <span style="opacity:0.6; font-weight:500; margin-left:2px;">vs prethodni period</span>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Chart --}}
        <div class="sa-card">
            <div class="sa-card-hd">
                <div>
                    <div class="sa-card-title">Kretanje prometa (dnevno)</div>
                    <div class="sa-card-sub">Prosjek: <strong>{{ number_format($avgRev, 2, ',', '.') }} KM</strong> · Vrh: <strong>{{ number_format($maxRev, 2, ',', '.') }} KM</strong></div>
                </div>
            </div>

            @if(collect($series)->sum('revenue') <= 0)
                <div class="sa-empty">Nema prodaja u ovom periodu.</div>
            @else
                <div class="sa-chart-wrap">
                    <svg class="sa-chart-svg" viewBox="0 0 {{ $svgW }} {{ $svgH }}" preserveAspectRatio="none" role="img" aria-label="Kretanje prometa">
                        {{-- Gridlines --}}
                        @foreach([0.25, 0.5, 0.75, 1.0] as $frac)
                            @php $gy = $padT + $usableH - ($usableH * $frac); @endphp
                            <line x1="{{ $padL }}" y1="{{ $gy }}" x2="{{ $svgW - $padR }}" y2="{{ $gy }}"
                                  stroke="currentColor" stroke-opacity="0.08" stroke-dasharray="3,4" />
                            <text x="{{ $padL - 6 }}" y="{{ $gy + 3 }}" text-anchor="end"
                                  font-size="9" fill="currentColor" fill-opacity="0.45">
                                {{ number_format($maxRev * $frac, 0, ',', '.') }}
                            </text>
                        @endforeach

                        {{-- Area --}}
                        <defs>
                            <linearGradient id="sa-grad" x1="0" x2="0" y1="0" y2="1">
                                <stop offset="0%"   stop-color="#BBA14F" stop-opacity="0.35"/>
                                <stop offset="100%" stop-color="#BBA14F" stop-opacity="0"/>
                            </linearGradient>
                        </defs>
                        <polygon points="{{ $areaPts }}" fill="url(#sa-grad)"/>

                        {{-- Line --}}
                        <polyline points="{{ $linePts }}" fill="none"
                                  stroke="#8b6914" stroke-width="2"
                                  stroke-linejoin="round" stroke-linecap="round"/>

                        {{-- Dots + hover tooltips (native SVG title) --}}
                        @foreach($points as $p)
                            <circle cx="{{ round($p[0],2) }}" cy="{{ round($p[1],2) }}" r="3"
                                    fill="#fff" stroke="#8b6914" stroke-width="1.6">
                                <title>{{ $p[2]['label'] }} · {{ number_format($p[2]['revenue'], 2, ',', '.') }} KM</title>
                            </circle>
                        @endforeach

                        {{-- X-axis labels (every ~5th so it doesn't crowd) --}}
                        @php $step = max(1, (int) ceil($cnt / 8)); @endphp
                        @foreach($points as $i => $p)
                            @if($i % $step === 0 || $i === $cnt - 1)
                                <text x="{{ round($p[0],2) }}" y="{{ $svgH - 8 }}"
                                      text-anchor="middle" font-size="9"
                                      fill="currentColor" fill-opacity="0.55">
                                    {{ $p[2]['label'] }}
                                </text>
                            @endif
                        @endforeach
                    </svg>
                </div>
            @endif
        </div>

        {{-- Leaderboards --}}
        <div class="sa-two">
            {{-- Top perfumes --}}
            <div class="sa-card">
                <div class="sa-card-hd">
                    <div>
                        <div class="sa-card-title">Najprodavaniji parfemi</div>
                        <div class="sa-card-sub">Rangirano po prometu</div>
                    </div>
                </div>

                @php $topPerfMax = collect($topPerf)->max('revenue') ?: 1; @endphp
                @forelse($topPerf as $i => $p)
                    <div class="sa-row">
                        <div class="sa-rank sa-rank--{{ $i + 1 }}">{{ $i + 1 }}</div>
                        <div class="sa-name">
                            <div class="sa-name-title">{{ $p['name'] }}</div>
                            @if(!empty($p['inspired_by']))
                                <div class="sa-name-sub">Inspirisano od: {{ $p['inspired_by'] }}</div>
                            @endif
                            <div class="sa-bar"><span style="width: {{ min(100, ($p['revenue'] / $topPerfMax) * 100) }}%"></span></div>
                        </div>
                        <div class="sa-metric">
                            <div class="sa-metric-val">{{ number_format($p['revenue'], 2, ',', '.') }} KM</div>
                            <div class="sa-metric-sub">{{ $p['units'] }} kom</div>
                        </div>
                    </div>
                @empty
                    <div class="sa-empty">Nema podataka.</div>
                @endforelse
            </div>

            {{-- Top sellers --}}
            <div class="sa-card">
                <div class="sa-card-hd">
                    <div>
                        <div class="sa-card-title">Najbolji prodavači</div>
                        <div class="sa-card-sub">Rangirano po prometu · {{ $this->groupTeam ? 'Sorénza tim spojen' : 'Svaki član posebno' }}</div>
                    </div>
                    <button type="button" wire:click="toggleGroupTeam" class="sa-chip">
                        @if($this->groupTeam)
                            Prikaži pojedinačno
                        @else
                            Spoji Sorénza tim
                        @endif
                    </button>
                </div>

                @php $topSellMax = collect($topSell)->max('revenue') ?: 1; @endphp
                @forelse($topSell as $i => $s)
                    <div class="sa-row">
                        <div class="sa-rank sa-rank--{{ $i + 1 }}">{{ $i + 1 }}</div>
                        <div class="sa-name">
                            <div class="sa-name-title" style="display:flex; align-items:center; gap:6px;">
                                {{ $s['name'] }}
                                @if(!empty($s['is_team']))
                                    <span style="display:inline-flex; align-items:center; gap:3px;
                                                 background: linear-gradient(90deg, #8b6914, #DBC584);
                                                 color: #fff; font-size:0.62rem; font-weight:700;
                                                 padding: 2px 7px; border-radius:999px;
                                                 letter-spacing: 0.06em; text-transform: uppercase;">
                                        Tim
                                        @if(!empty($s['members']))
                                            · {{ $s['members'] }}
                                        @endif
                                    </span>
                                @endif
                            </div>
                            <div class="sa-bar"><span style="width: {{ min(100, ($s['revenue'] / $topSellMax) * 100) }}%"></span></div>
                        </div>
                        <div class="sa-metric">
                            <div class="sa-metric-val">{{ number_format($s['revenue'], 2, ',', '.') }} KM</div>
                            <div class="sa-metric-sub">{{ $s['units'] }} kom</div>
                        </div>
                    </div>
                @empty
                    <div class="sa-empty">Nema podataka.</div>
                @endforelse
            </div>
        </div>

        {{-- Cohorts: new vs returning customers per month --}}
        @php
            $cohortMax = 1;
            foreach ($cohorts as $c) { $cohortMax = max($cohortMax, $c['new'] + $c['returning']); }
            $totalNew = collect($cohorts)->sum('new');
            $totalRet = collect($cohorts)->sum('returning');
            $totalAll = $totalNew + $totalRet;
            $retPct   = $totalAll > 0 ? round(($totalRet / $totalAll) * 100, 1) : 0;
        @endphp
        <div class="sa-card">
            <div class="sa-card-hd">
                <div>
                    <div class="sa-card-title">Novi vs vraćeni kupci (mjesečno)</div>
                    <div class="sa-card-sub">
                        Ukupno: <strong>{{ $totalAll }}</strong> narudžbi ·
                        Novi: <strong>{{ $totalNew }}</strong> ·
                        Vraćeni: <strong>{{ $totalRet }}</strong> ·
                        <span style="color:#8b6914; font-weight:600;">{{ number_format($retPct, 1, ',', '.') }}% zadržavanje</span>
                    </div>
                </div>
                <div style="display:inline-flex; gap:12px; font-size:0.75rem;">
                    <span style="display:inline-flex; align-items:center; gap:6px;">
                        <span style="width:10px; height:10px; border-radius:3px; background:#BBA14F;"></span> Novi
                    </span>
                    <span style="display:inline-flex; align-items:center; gap:6px;">
                        <span style="width:10px; height:10px; border-radius:3px; background:#1f2937;"></span> Vraćeni
                    </span>
                </div>
            </div>

            @if($totalAll === 0)
                <div class="sa-empty">Nema narudžbi u ovom periodu.</div>
            @else
                <div class="sa-cohorts" style="display: grid; gap: 12px; padding: 0.75rem 0 1rem;">
                    @foreach($cohorts as $c)
                        @php
                            $total = $c['new'] + $c['returning'];
                            $newPct = $total > 0 ? ($c['new'] / $cohortMax) * 100 : 0;
                            $retPctBar = $total > 0 ? ($c['returning'] / $cohortMax) * 100 : 0;
                        @endphp
                        <div style="display:grid; grid-template-columns: 90px 1fr 120px; align-items:center; gap:10px;">
                            <div style="font-size:0.78rem; color:#6b7280; font-weight:600; text-transform: capitalize;">{{ $c['label'] }}</div>
                            <div style="display:flex; height:22px; border-radius:6px; overflow:hidden; background: rgba(0,0,0,0.04);">
                                @if($c['new'] > 0)
                                    <div title="Novi: {{ $c['new'] }}" style="width:{{ $newPct }}%; background: linear-gradient(180deg, #DBC584, #BBA14F); display:flex; align-items:center; justify-content:center; color:#111827; font-size:0.7rem; font-weight:700;">
                                        @if($newPct > 8){{ $c['new'] }}@endif
                                    </div>
                                @endif
                                @if($c['returning'] > 0)
                                    <div title="Vraćeni: {{ $c['returning'] }}" style="width:{{ $retPctBar }}%; background: linear-gradient(180deg, #1f2937, #111827); display:flex; align-items:center; justify-content:center; color:#fff; font-size:0.7rem; font-weight:700;">
                                        @if($retPctBar > 8){{ $c['returning'] }}@endif
                                    </div>
                                @endif
                            </div>
                            <div style="text-align:right; font-size:0.78rem; color:#6b7280;">
                                <strong style="color: #111827;">{{ $total }}</strong> narudžbi
                            </div>
                        </div>
                    @endforeach
                </div>

                <style>
                    .dark .sa-cohorts > div > div:first-child { color: #9ca3af !important; }
                    .dark .sa-cohorts > div > div:last-child  { color: #9ca3af !important; }
                    .dark .sa-cohorts > div > div:last-child > strong { color: #f3f4f6 !important; }
                    .dark .sa-cohorts > div > div:nth-child(2) { background: rgba(255,255,255,0.06) !important; }
                </style>
            @endif
        </div>

    </div>
</x-filament-panels::page>
