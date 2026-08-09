<x-filament-panels::page>
    @if(! $configured)
        <div style="padding: 2rem; text-align: center; border: 1px dashed rgba(0,0,0,0.15); border-radius: 1rem; color: #6b7280;">
            <strong>Google Analytics nije konfigurisan.</strong>
            <p style="margin-top: 0.5rem; font-size: 0.85rem;">Postavite <code>GA_PROPERTY_ID</code> i <code>GA_CREDENTIALS_PATH</code> u <code>.env</code>.</p>
        </div>
    @else
        @php
            $svgW = 800; $svgH = 200; $padL = 32; $padR = 12; $padT = 20; $padB = 24;
            $cnt = max(1, count($series));
            $stepX = ($svgW - $padL - $padR) / max(1, $cnt - 1);
            $usableH = $svgH - $padT - $padB;
            $maxV = max(1, collect($series)->max('sessions') ?: 1);
            $points = [];
            foreach ($series as $i => $s) {
                $x = $padL + $stepX * $i;
                $y = $padT + $usableH - (($s['sessions'] / $maxV) * $usableH);
                $points[] = [$x, $y, $s];
            }
            $linePts = implode(' ', array_map(fn($p) => round($p[0],2).','.round($p[1],2), $points));
            $areaPts = $linePts . ' ' . round($padL + $stepX * ($cnt - 1),2) . ','.($svgH - $padB).' ' . $padL . ','.($svgH - $padB);
            $devMap  = ['desktop' => 'Desktop', 'mobile' => 'Mobitel', 'tablet' => 'Tablet', 'smart tv' => 'TV'];
            $devTotal = max(1, collect($devices)->sum('sessions'));
        @endphp

        <style>
            .sv-wrap { display:flex; flex-direction:column; gap:1rem; }
            .sv-head { display:flex; flex-direction:column; gap:0.75rem; padding:0.9rem 1.1rem; border-radius:1rem; border:1px solid rgba(59,130,246,0.22); background: radial-gradient(600px 200px at 100% -50px, rgba(96,165,250,0.14), transparent 60%), linear-gradient(180deg,#f8fafc,#ffffff); }
            .dark .sv-head { background: radial-gradient(600px 200px at 100% -50px, rgba(96,165,250,0.08), transparent 60%), linear-gradient(180deg,#17181b,#131315); border-color: rgba(96,165,250,0.18); }
            .sv-eyebrow { font-size:0.66rem; letter-spacing:0.24em; text-transform:uppercase; color:#1d4ed8; font-weight:600; }
            .dark .sv-eyebrow { color:#93c5fd; }
            .sv-title { font-family:'Cormorant Garamond',Georgia,serif; font-style:italic; font-weight:600; font-size:1.4rem; line-height:1.15; margin:0.15rem 0 0.25rem; color:#111827; }
            .dark .sv-title { color:#f3f4f6; }
            .sv-sub { color:#6b7280; font-size:0.8rem; }
            .dark .sv-sub { color:#9ca3af; }
            .sv-realtime { display:inline-flex; align-items:center; gap:6px; padding:5px 10px; border-radius:999px; background:#dcfce7; color:#166534; font-size:0.72rem; font-weight:700; }
            .sv-realtime::before { content:''; width:6px; height:6px; border-radius:999px; background:#22c55e; animation:sv-pulse 1.4s infinite; }
            .dark .sv-realtime { background:rgba(34,197,94,0.15); color:#86efac; }
            @keyframes sv-pulse { 0%,100%{opacity:1} 50%{opacity:.4} }
            .sv-presets { display:flex; flex-wrap:wrap; gap:0.4rem; }
            .sv-chip { font-size:0.75rem; font-weight:600; padding:0.4rem 0.85rem; border-radius:999px; border:1px solid rgba(0,0,0,0.10); background:#fff !important; color:#374151 !important; cursor:pointer; transition:all .15s ease; line-height:1; }
            .sv-chip:hover { background:#f9fafb !important; color:#111827 !important; }
            .dark .sv-chip { background:#1c1d20 !important; color:#e5e7eb !important; border-color:rgba(255,255,255,0.10); }
            .dark .sv-chip:hover { background:#24252a !important; color:#ffffff !important; }
            .sv-chip--active, .sv-chip--active:hover { background:#1d4ed8 !important; color:#ffffff !important; border-color:transparent !important; box-shadow:0 0 0 2px #60a5fa, 0 6px 14px -8px rgba(29,78,216,0.5) !important; }
            .dark .sv-chip--active, .dark .sv-chip--active:hover { background:#3b82f6 !important; color:#f8fafc !important; box-shadow:0 0 0 2px rgba(255,255,255,0.15), 0 6px 14px -8px rgba(59,130,246,0.35) !important; }

            .sv-kpis { display:grid; grid-template-columns:repeat(1,1fr); gap:0.7rem; }
            @media(min-width:640px){.sv-kpis{grid-template-columns:repeat(2,1fr);}}
            @media(min-width:1024px){.sv-kpis{grid-template-columns:repeat(4,1fr);}}
            .sv-kpi { position:relative; overflow:hidden; padding:0.9rem 1rem; border-radius:1rem; border:1px solid rgba(0,0,0,0.06); background:#fff; box-shadow:0 1px 2px rgba(17,24,39,0.04); }
            .dark .sv-kpi { background:#17181b; border-color:rgba(255,255,255,0.06); }
            .sv-kpi::before { content:''; position:absolute; inset:0 0 auto 0; height:3px; background:linear-gradient(90deg,var(--sk1,#1d4ed8),var(--sk2,#60a5fa)); }
            .sv-kpi-label { font-size:0.66rem; letter-spacing:0.14em; text-transform:uppercase; color:#6b7280; font-weight:600; }
            .dark .sv-kpi-label { color:#9ca3af; }
            .sv-kpi-icon { position:absolute; right:0.7rem; top:0.8rem; opacity:0.32; color:var(--sk1); }
            .sv-kpi-value { margin-top:0.3rem; font-size:1.4rem; font-weight:700; color:#111827; overflow-wrap:anywhere; }
            .dark .sv-kpi-value { color:#f3f4f6; }
            .sv-kpi-delta { margin-top:0.35rem; display:inline-flex; align-items:center; gap:4px; font-size:0.68rem; font-weight:600; padding:0.16rem 0.5rem; border-radius:999px; }
            .sv-kpi-delta--up { color:#065f46; background:rgba(16,185,129,0.12); }
            .sv-kpi-delta--down { color:#991b1b; background:rgba(239,68,68,0.12); }
            .sv-kpi-delta--flat { color:#6b7280; background:rgba(107,114,128,0.12); }
            .dark .sv-kpi-delta--up { color:#6ee7b7; background:rgba(16,185,129,0.14); }
            .dark .sv-kpi-delta--down { color:#fca5a5; background:rgba(239,68,68,0.15); }
            .dark .sv-kpi-delta--flat { color:#d1d5db; background:rgba(255,255,255,0.06); }

            .sv-card { padding:0.9rem 1rem; border-radius:1rem; border:1px solid rgba(0,0,0,0.06); background:#fff; box-shadow:0 1px 2px rgba(17,24,39,0.04); }
            .dark .sv-card { background:#17181b; border-color:rgba(255,255,255,0.06); }
            .sv-card-title { font-weight:700; font-size:0.9rem; color:#111827; }
            .dark .sv-card-title { color:#f3f4f6; }
            .sv-card-sub { font-size:0.72rem; color:#6b7280; margin-top:2px; }
            .dark .sv-card-sub { color:#9ca3af; }
            .sv-two { display:grid; grid-template-columns:1fr; gap:1rem; }
            @media(min-width:1024px){.sv-two{grid-template-columns:2fr 1fr;}}

            .sv-row { display:flex; align-items:center; gap:0.7rem; padding:0.55rem 0; border-bottom:1px dashed rgba(0,0,0,0.06); }
            .dark .sv-row { border-bottom-color:rgba(255,255,255,0.06); }
            .sv-row:last-child { border-bottom:0; }
            .sv-row-name { flex:1 1 auto; min-width:0; }
            .sv-row-title { font-weight:600; color:#111827; font-size:0.85rem; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
            .dark .sv-row-title { color:#f3f4f6; }
            .sv-row-sub { font-size:0.7rem; color:#6b7280; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
            .dark .sv-row-sub { color:#9ca3af; }
            .sv-row-metric { flex:0 0 auto; font-weight:700; color:#111827; font-size:0.85rem; }
            .dark .sv-row-metric { color:#f3f4f6; }
            .sv-bar { margin-top:5px; height:4px; border-radius:999px; background:rgba(0,0,0,0.06); overflow:hidden; }
            .dark .sv-bar { background:rgba(255,255,255,0.06); }
            .sv-bar > span { display:block; height:100%; background:linear-gradient(90deg,#1d4ed8,#60a5fa); }
            .sv-empty { text-align:center; padding:1.5rem 0.5rem; color:#6b7280; font-size:0.8rem; font-style:italic; }
            .dark .sv-empty { color:#9ca3af; }
        </style>

        <div class="sv-wrap">
            <div class="sv-head">
                <div style="display:flex; justify-content:space-between; align-items:flex-start; gap:8px; flex-wrap:wrap;">
                    <div>
                        <div class="sv-eyebrow">Sorénza · Google Analytics</div>
                        <div class="sv-title">Posjete sajta</div>
                        <div class="sv-sub"><em>{{ $rangeInfo['label'] }}</em></div>
                    </div>
                    <div class="sv-realtime" wire:poll.20s>{{ $realtime }} aktivnih sada</div>
                </div>
                <div class="sv-presets">
                    @foreach($presets as $k => $l)
                        <button type="button" wire:click="setRange('{{ $k }}')" class="sv-chip {{ $this->range === $k ? 'sv-chip--active' : '' }}">{{ $l }}</button>
                    @endforeach

                    <span style="flex:1;"></span>

                    <button type="button" wire:click="refreshCache" class="sv-chip" style="border-color: rgba(29,78,216,0.35);">
                        ↻ Osvježi
                    </button>
                    <button type="button" wire:click="runDiagnostic" class="sv-chip" style="border-color: rgba(220,38,38,0.35); color:#991b1b !important;">
                        🔧 Provjeri GA konekciju
                    </button>
                </div>
            </div>

            {{-- KPIs --}}
            <div class="sv-kpis">
                @foreach($kpis as $k)
                    @php
                        $delta = $k['delta'];
                        $cls = $delta === null ? 'sv-kpi-delta--flat' : ($delta > 0.5 ? 'sv-kpi-delta--up' : ($delta < -0.5 ? 'sv-kpi-delta--down' : 'sv-kpi-delta--flat'));
                        $palette = match($k['tone']) {
                            'emerald' => ['#059669','#34d399'],
                            'indigo'  => ['#4f46e5','#818cf8'],
                            'rose'    => ['#e11d48','#fda4af'],
                            default   => ['#1d4ed8','#60a5fa'],
                        };
                    @endphp
                    <div class="sv-kpi" style="--sk1:{{$palette[0]}};--sk2:{{$palette[1]}};">
                        <div class="sv-kpi-icon">@svg($k['icon'], 'w-5 h-5')</div>
                        <div class="sv-kpi-label">{{ $k['label'] }}</div>
                        <div class="sv-kpi-value">{{ $k['value'] }}</div>
                        <div class="sv-kpi-delta {{ $cls }}">
                            @if($delta === null)—
                            @else
                                @if($delta >= 0)+@endif{{ number_format($delta, 1, ',', '.') }}%
                            @endif
                            <span style="opacity:0.6; font-weight:500; margin-left:2px;">vs prethodni</span>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Chart + engagement pill --}}
            <div class="sv-card">
                <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:8px; margin-bottom:8px;">
                    <div>
                        <div class="sv-card-title">Sesije kroz vrijeme</div>
                        <div class="sv-card-sub">Prosj. trajanje sesije: <strong>{{ $avgSession }}</strong> · Bounce rate: <strong>{{ $bounceRate }}%</strong></div>
                    </div>
                </div>
                @if(collect($series)->sum('sessions') <= 0)
                    <div class="sv-empty">Još nema podataka o posjetama u ovom periodu.</div>
                @else
                    <div style="overflow-x:auto;">
                        <svg viewBox="0 0 {{ $svgW }} {{ $svgH }}" preserveAspectRatio="none" style="display:block; width:100%; min-width:320px;">
                            @foreach([0.25,0.5,0.75,1.0] as $f)
                                @php $gy=$padT+$usableH-($usableH*$f); @endphp
                                <line x1="{{ $padL }}" y1="{{ $gy }}" x2="{{ $svgW-$padR }}" y2="{{ $gy }}" stroke="currentColor" stroke-opacity="0.08" stroke-dasharray="3,4"/>
                            @endforeach
                            <defs>
                                <linearGradient id="sv-grad" x1="0" x2="0" y1="0" y2="1">
                                    <stop offset="0%" stop-color="#60a5fa" stop-opacity="0.35"/>
                                    <stop offset="100%" stop-color="#60a5fa" stop-opacity="0"/>
                                </linearGradient>
                            </defs>
                            <polygon points="{{ $areaPts }}" fill="url(#sv-grad)"/>
                            <polyline points="{{ $linePts }}" fill="none" stroke="#1d4ed8" stroke-width="2" stroke-linejoin="round" stroke-linecap="round"/>
                            @foreach($points as $p)
                                <circle cx="{{ round($p[0],2) }}" cy="{{ round($p[1],2) }}" r="3" fill="#fff" stroke="#1d4ed8" stroke-width="1.6">
                                    <title>{{ $p[2]['label'] }} · {{ $p[2]['sessions'] }} sesija, {{ $p[2]['users'] }} posjetilaca</title>
                                </circle>
                            @endforeach
                            @php $step = max(1, (int) ceil($cnt/8)); @endphp
                            @foreach($points as $i => $p)
                                @if($i%$step===0 || $i===$cnt-1)
                                    <text x="{{ round($p[0],2) }}" y="{{ $svgH-6 }}" text-anchor="middle" font-size="9" fill="currentColor" fill-opacity="0.55">{{ $p[2]['label'] }}</text>
                                @endif
                            @endforeach
                        </svg>
                    </div>
                @endif
            </div>

            {{-- Two-col: top pages + top sources --}}
            <div class="sv-two">
                <div class="sv-card">
                    <div class="sv-card-title">Najposjećenije stranice</div>
                    <div class="sv-card-sub">Rangirano po broju pregleda</div>
                    @php $mp = collect($topPages)->max('pageviews') ?: 1; @endphp
                    <div style="margin-top:10px;">
                        @forelse($topPages as $p)
                            <div class="sv-row">
                                <div class="sv-row-name">
                                    <div class="sv-row-title">{{ $p['title'] ?: $p['path'] }}</div>
                                    <div class="sv-row-sub">{{ $p['path'] }}</div>
                                    <div class="sv-bar"><span style="width: {{ min(100, ($p['pageviews']/$mp)*100) }}%"></span></div>
                                </div>
                                <div class="sv-row-metric" style="text-align:right;">
                                    {{ number_format($p['pageviews'], 0, ',', '.') }}
                                    <div class="sv-row-sub" style="font-weight:500;">{{ $p['users'] }} posj.</div>
                                </div>
                            </div>
                        @empty
                            <div class="sv-empty">Nema podataka.</div>
                        @endforelse
                    </div>
                </div>

                <div class="sv-card">
                    <div class="sv-card-title">Izvori posjeta</div>
                    <div class="sv-card-sub">Odakle dolaze posjetioci</div>
                    @php $ms = collect($topSources)->max('sessions') ?: 1; @endphp
                    <div style="margin-top:10px;">
                        @forelse($topSources as $s)
                            <div class="sv-row">
                                <div class="sv-row-name">
                                    <div class="sv-row-title">{{ $s['channel'] }}</div>
                                    <div class="sv-bar"><span style="width: {{ min(100, ($s['sessions']/$ms)*100) }}%"></span></div>
                                </div>
                                <div class="sv-row-metric">{{ number_format($s['sessions'], 0, ',', '.') }}</div>
                            </div>
                        @empty
                            <div class="sv-empty">Nema podataka.</div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Devices --}}
            <div class="sv-card">
                <div class="sv-card-title">Uređaji</div>
                <div class="sv-card-sub">Mobitel vs desktop vs tablet</div>
                <div style="margin-top:12px; display:grid; grid-template-columns:repeat(auto-fit, minmax(180px, 1fr)); gap:10px;">
                    @forelse($devices as $d)
                        @php
                            $label = $devMap[strtolower($d['device'])] ?? ucfirst($d['device']);
                            $pct = ($d['sessions'] / $devTotal) * 100;
                        @endphp
                        <div style="padding:12px; border-radius:0.75rem; background:rgba(59,130,246,0.05); border:1px solid rgba(59,130,246,0.15);">
                            <div style="font-size:0.72rem; letter-spacing:0.14em; text-transform:uppercase; color:#1d4ed8; font-weight:700;">{{ $label }}</div>
                            <div style="font-size:1.4rem; font-weight:700; color:#111827; margin-top:2px;">{{ number_format($d['sessions'], 0, ',', '.') }}</div>
                            <div style="font-size:0.72rem; color:#6b7280;">{{ number_format($pct, 1, ',', '.') }}% sesija</div>
                            <div class="sv-bar" style="margin-top:6px;"><span style="width:{{ $pct }}%"></span></div>
                        </div>
                    @empty
                        <div class="sv-empty" style="grid-column:1/-1;">Nema podataka.</div>
                    @endforelse
                </div>
            </div>

            <div style="text-align:center; font-size:0.72rem; color:#9ca3af; padding:6px;">
                Podaci se osvježavaju svakih 5 minuta iz Google Analytics-a.
            </div>
        </div>
    @endif
</x-filament-panels::page>
