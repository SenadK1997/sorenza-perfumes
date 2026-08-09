<x-filament-widgets::widget>
    <div class="pr-alert">
        <div class="pr-alert__side">
            <div class="pr-alert__badge">{{ $count }}</div>
        </div>

        <div class="pr-alert__body">
            <div class="pr-alert__title">
                @if($count === 1)
                    1 novi zahtjev za povrat čeka Vas
                @else
                    {{ $count }} novih zahtjeva za povrat čeka Vas
                @endif
            </div>
            <div class="pr-alert__desc">
                Odmah odgovorite kupcu — brz odgovor smanjuje frustraciju i loše recenzije.
            </div>

            @if($latest->count())
                <ul class="pr-alert__list">
                    @foreach($latest as $r)
                        <li>
                            <span class="pr-alert__order">#{{ $r->order?->pretty_id }}</span>
                            <span class="pr-alert__cust">{{ $r->customer_name }}</span>
                            <span class="pr-alert__ago">· {{ $r->created_at->diffForHumans() }}</span>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

        <div class="pr-alert__cta">
            <a href="{{ $listUrl }}" class="pr-alert__btn">
                Riješi zahtjeve
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" width="14" height="14">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
        </div>
    </div>

    <style>
        .pr-alert {
            display: flex; align-items: center; gap: 16px;
            padding: 16px 18px; border-radius: 16px;
            background: linear-gradient(120deg, #fff1f0, #ffe4e1 60%, #ffe8d6);
            border: 1px solid rgba(220, 38, 38, 0.25);
            box-shadow: 0 6px 22px -12px rgba(220, 38, 38, 0.3);
            position: relative; overflow: hidden;
        }
        .dark .pr-alert {
            background: linear-gradient(120deg, rgba(127,29,29,0.35), rgba(69,10,10,0.35));
            border-color: rgba(220, 38, 38, 0.35);
        }
        .pr-alert::before {
            content: ''; position: absolute; inset: 0 0 auto 0; height: 3px;
            background: linear-gradient(90deg, #dc2626, #f59e0b);
        }
        .pr-alert__side { flex: 0 0 auto; }
        .pr-alert__badge {
            width: 48px; height: 48px; border-radius: 999px;
            background: linear-gradient(135deg, #dc2626, #f97316);
            color: #fff; font-weight: 800; font-size: 20px;
            display: inline-flex; align-items: center; justify-content: center;
            box-shadow: 0 6px 14px -6px rgba(220,38,38,0.55);
            animation: pr-pulse 1.8s ease-in-out infinite;
        }
        @keyframes pr-pulse {
            0%,100% { transform: scale(1);   box-shadow: 0 0 0 0 rgba(220,38,38,0.35); }
            50%     { transform: scale(1.05); box-shadow: 0 0 0 12px rgba(220,38,38,0);  }
        }
        .pr-alert__body { flex: 1 1 auto; min-width: 0; }
        .pr-alert__title {
            font-weight: 700; font-size: 15px; color: #7f1d1d; line-height: 1.3;
        }
        .dark .pr-alert__title { color: #fecaca; }
        .pr-alert__desc {
            font-size: 12.5px; color: #991b1b; margin-top: 2px;
        }
        .dark .pr-alert__desc { color: #fca5a5; }
        .pr-alert__list {
            margin: 8px 0 0; padding: 0; list-style: none;
            display: flex; flex-direction: column; gap: 3px;
        }
        .pr-alert__list li {
            font-size: 12px; color: #7f1d1d;
            overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
        }
        .dark .pr-alert__list li { color: #fecaca; }
        .pr-alert__order { font-weight: 700; margin-right: 4px; }
        .pr-alert__cust  { }
        .pr-alert__ago   { opacity: 0.7; }

        .pr-alert__cta { flex: 0 0 auto; }
        .pr-alert__btn {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 10px 16px; border-radius: 999px;
            background: #7f1d1d; color: #ffffff;
            font-weight: 700; font-size: 12.5px;
            text-decoration: none; letter-spacing: 0.02em;
            box-shadow: 0 6px 14px -6px rgba(127,29,29,0.6);
            transition: all .15s ease;
            white-space: nowrap;
        }
        .pr-alert__btn:hover { background: #991b1b; transform: translateY(-1px); }
        .dark .pr-alert__btn { background: #dc2626; }
        .dark .pr-alert__btn:hover { background: #ef4444; }

        @media (max-width: 640px) {
            .pr-alert { flex-direction: column; align-items: stretch; }
            .pr-alert__cta .pr-alert__btn { justify-content: center; width: 100%; }
        }
    </style>
</x-filament-widgets::widget>
