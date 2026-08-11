<x-filament-panels::page>
    <style>
        .dl-wrap { display:flex; flex-direction:column; gap:1rem; }
        .dl-cats { display:flex; flex-wrap:wrap; gap:0.4rem; }
        .dl-chip { font-size:0.75rem; font-weight:600; padding:0.4rem 0.85rem; border-radius:999px; border:1px solid rgba(0,0,0,0.10); background:#fff !important; color:#374151 !important; cursor:pointer; transition:all .15s ease; line-height:1; }
        .dl-chip:hover { background:#f9fafb !important; color:#111827 !important; }
        .dark .dl-chip { background:#1c1d20 !important; color:#e5e7eb !important; border-color:rgba(255,255,255,0.10); }
        .dl-chip--active, .dl-chip--active:hover {
            background:#111827 !important; color:#fff !important; border-color:transparent !important;
            box-shadow:0 0 0 2px #BBA14F, 0 6px 14px -8px rgba(139,105,20,0.5) !important;
        }
        .dark .dl-chip--active { background:#DBC584 !important; color:#111827 !important; }

        .dl-grid { display:grid; grid-template-columns:1fr; gap:0.75rem; }
        @media(min-width:640px){.dl-grid{grid-template-columns:repeat(2,1fr);}}
        @media(min-width:1024px){.dl-grid{grid-template-columns:repeat(3,1fr);}}

        .dl-card { display:flex; flex-direction:column; gap:0.6rem; padding:1rem; border-radius:1rem; border:1px solid rgba(187,161,79,0.22); background:#fff; transition:all .15s ease; }
        .dark .dl-card { background:#17181b; border-color:rgba(187,161,79,0.16); }
        .dl-card:hover { transform:translateY(-1px); box-shadow:0 10px 22px -12px rgba(139,105,20,0.25); border-color:rgba(187,161,79,0.4); }
        .dl-head { display:flex; align-items:flex-start; gap:0.75rem; }
        .dl-icon { flex:0 0 auto; width:42px; height:42px; border-radius:0.7rem;
                   display:inline-flex; align-items:center; justify-content:center;
                   background:linear-gradient(135deg,#8b6914,#DBC584); color:#fff; }
        .dl-body { flex:1 1 auto; min-width:0; }
        .dl-title { font-weight:700; color:#111827; font-size:0.92rem; line-height:1.3; overflow-wrap:anywhere; }
        .dark .dl-title { color:#f3f4f6; }
        .dl-desc { font-size:0.78rem; color:#6b7280; margin-top:3px; line-height:1.4; overflow-wrap:anywhere; }
        .dark .dl-desc { color:#9ca3af; }
        .dl-meta { display:flex; align-items:center; gap:6px; flex-wrap:wrap; font-size:0.7rem; color:#6b7280; margin-top:3px; }
        .dark .dl-meta { color:#9ca3af; }
        .dl-badge { display:inline-block; font-size:0.62rem; font-weight:700; padding:2px 8px; border-radius:999px; text-transform:uppercase; letter-spacing:0.06em; }
        .dl-badge--catalog   { background:#dbeafe; color:#1e40af; }
        .dl-badge--price     { background:#dcfce7; color:#166534; }
        .dl-badge--guide     { background:#ede9fe; color:#5b21b6; }
        .dl-badge--marketing { background:#fef3c7; color:#92400e; }
        .dl-badge--other     { background:#e5e7eb; color:#374151; }
        .dark .dl-badge--catalog { background:rgba(59,130,246,0.15); color:#93c5fd; }
        .dark .dl-badge--price { background:rgba(34,197,94,0.15); color:#86efac; }
        .dark .dl-badge--guide { background:rgba(139,92,246,0.15); color:#c4b5fd; }
        .dark .dl-badge--marketing { background:rgba(245,158,11,0.15); color:#fcd34d; }
        .dark .dl-badge--other { background:rgba(255,255,255,0.06); color:#d1d5db; }

        .dl-btn { display:inline-flex; align-items:center; justify-content:center; gap:6px;
                  padding:0.55rem 1rem; border-radius:999px;
                  background:linear-gradient(90deg,#8b6914,#BBA14F,#DBC584); color:#fff !important;
                  font-weight:700; font-size:0.82rem; text-decoration:none;
                  box-shadow:0 4px 12px -6px rgba(139,105,20,0.55); transition:all .15s ease; }
        .dl-btn:hover { filter:brightness(1.05); transform:translateY(-1px); }

        .dl-empty { padding:3rem 1rem; text-align:center; color:#6b7280; font-size:0.9rem; }
        .dark .dl-empty { color:#9ca3af; }
    </style>

    <div class="dl-wrap">

        {{-- Category filter --}}
        <div class="dl-cats">
            @foreach($categories as $key => $label)
                <button type="button" wire:click="setCategory('{{ $key }}')" class="dl-chip {{ $this->category === $key ? 'dl-chip--active' : '' }}">
                    {{ $label }}
                </button>
            @endforeach
        </div>

        @if($files->isEmpty())
            <div class="dl-empty">
                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.2" style="margin: 0 auto 12px; opacity:0.4;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 014.5 9.75h15A2.25 2.25 0 0121.75 12v.75m-8.69-6.44l-2.12-2.12a1.5 1.5 0 00-1.061-.44H4.5A2.25 2.25 0 002.25 6v12a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9a2.25 2.25 0 00-2.25-2.25h-5.379a1.5 1.5 0 01-1.06-.44z"/>
                </svg>
                <div style="font-style:italic;">Trenutno nema fajlova u ovoj kategoriji.</div>
            </div>
        @else
            <div class="dl-grid">
                @foreach($files as $f)
                    <div class="dl-card">
                        <div class="dl-head">
                            <div class="dl-icon">
                                @svg($f->icon, 'w-6 h-6')
                            </div>
                            <div class="dl-body">
                                <div class="dl-title">{{ $f->title }}</div>
                                @if($f->description)
                                    <div class="dl-desc">{{ $f->description }}</div>
                                @endif
                                <div class="dl-meta">
                                    @if($f->category)
                                        <span class="dl-badge dl-badge--{{ $f->category }}">
                                            {{ ($categories[$f->category] ?? $f->category) }}
                                        </span>
                                    @endif
                                    <span>·</span>
                                    <span>{{ $f->size_human }}</span>
                                    <span>·</span>
                                    <span>{{ $f->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>

                        <div>
                            <a href="{{ $f->url }}" target="_blank" rel="noopener" class="dl-btn">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v14m0 0l-5-5m5 5l5-5M4 21h16"/>
                                </svg>
                                Preuzmi
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-filament-panels::page>
