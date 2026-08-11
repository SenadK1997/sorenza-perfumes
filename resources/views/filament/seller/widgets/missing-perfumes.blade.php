<x-filament-widgets::widget>
    <div class="mp-card">
        <div class="mp-head">
            <div class="mp-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="mp-title-wrap">
                <div class="mp-title">Nedostaje u lageru — {{ $missing->count() }}</div>
                <div class="mp-sub">Aktivni parfemi koje nemate na stanju</div>
            </div>
            <a href="{{ $requestUrl }}" class="mp-btn">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.4"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                Zatraži
            </a>
        </div>

        <div class="mp-chips">
            @foreach($missing as $p)
                <span class="mp-chip">{{ $p->name }}</span>
            @endforeach
        </div>
    </div>

    <style>
        .mp-card {
            padding: 0.85rem 1rem;
            border-radius: 1rem;
            border: 1px solid rgba(220,38,38,0.20);
            background: linear-gradient(120deg, #fff5f5, #fff 60%);
        }
        .dark .mp-card {
            background: linear-gradient(120deg, #1c1614, #17181b 60%);
            border-color: rgba(220,38,38,0.28);
        }
        .mp-head {
            display: flex; align-items: center; gap: 10px; margin-bottom: 10px;
        }
        .mp-icon {
            flex: 0 0 auto; width: 34px; height: 34px;
            border-radius: 999px;
            background: rgba(220,38,38,0.10); color: #b91c1c;
            display: inline-flex; align-items: center; justify-content: center;
        }
        .dark .mp-icon { background: rgba(220,38,38,0.18); color: #fca5a5; }
        .mp-title-wrap { flex: 1 1 auto; min-width: 0; }
        .mp-title { font-weight: 700; color: #7f1d1d; font-size: 0.92rem; line-height: 1.25; }
        .dark .mp-title { color: #fecaca; }
        .mp-sub { font-size: 0.72rem; color: #991b1b; opacity: 0.7; }
        .dark .mp-sub { color: #fca5a5; opacity: 0.7; }
        .mp-btn {
            display: inline-flex; align-items: center; gap: 5px;
            padding: 0.4rem 0.85rem; border-radius: 999px;
            background: #7f1d1d; color: #ffffff !important;
            text-decoration: none;
            font-weight: 700; font-size: 0.72rem;
            letter-spacing: 0.06em; text-transform: uppercase;
            white-space: nowrap;
            transition: all .15s ease;
        }
        .mp-btn:hover { background: #991b1b; transform: translateY(-1px); }
        .dark .mp-btn { background: #dc2626; }
        .dark .mp-btn:hover { background: #ef4444; }

        .mp-chips {
            display: flex; flex-wrap: wrap; gap: 5px;
        }
        .mp-chip {
            display: inline-block;
            padding: 3px 9px;
            border-radius: 6px;
            font-family: 'Menlo', 'Consolas', monospace;
            font-size: 0.78rem;
            font-weight: 700;
            background: #fff;
            color: #7f1d1d;
            border: 1px solid rgba(220,38,38,0.28);
            line-height: 1.3;
        }
        .dark .mp-chip {
            background: rgba(220,38,38,0.10);
            color: #fca5a5;
            border-color: rgba(220,38,38,0.30);
        }
    </style>
</x-filament-widgets::widget>
