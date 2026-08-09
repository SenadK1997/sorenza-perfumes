<x-filament-widgets::widget>
    <div class="sorenza-req-cta">
        <div class="sorenza-req-cta__icon" aria-hidden="true">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" width="20" height="20">
                <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8 4-8-4m16 0v10a2 2 0 01-2 2H6a2 2 0 01-2-2V7m16 0L12 3 4 7"/>
            </svg>
        </div>

        <div class="sorenza-req-cta__text">
            <div class="sorenza-req-cta__title">Treba Vam još parfema?</div>
            <div class="sorenza-req-cta__desc">
                Pošaljite zahtjev — admin ga odobrava, količina se automatski dodaje na Vaš lager.
            </div>
            @if($pendingCount > 0)
                <a href="{{ $indexUrl }}" class="sorenza-req-cta__pending">
                    <span class="sorenza-req-cta__dot"></span>
                    {{ $pendingCount }} zahtjev{{ $pendingCount == 1 ? '' : 'a' }} na čekanju
                </a>
            @endif
        </div>

        <div class="sorenza-req-cta__actions">
            <a href="{{ $indexUrl }}" class="sorenza-req-cta__btn sorenza-req-cta__btn--ghost">
                Moji zahtjevi
            </a>
            <a href="{{ $createUrl }}" class="sorenza-req-cta__btn sorenza-req-cta__btn--primary">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" width="16" height="16">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                </svg>
                Zatraži parfeme
            </a>
        </div>
    </div>

    <style>
        .sorenza-req-cta {
            display: flex;
            flex-direction: row;
            align-items: center;
            gap: 1rem;
            padding: 1rem 1.25rem;
            border-radius: 1rem;
            border: 1px solid rgba(187, 161, 79, 0.28);
            background: #ffffff;
            box-shadow: 0 1px 2px rgba(17,24,39,0.04), 0 6px 20px -14px rgba(139,105,20,0.20);
        }
        .dark .sorenza-req-cta {
            background: #17181b;
            border-color: rgba(187, 161, 79, 0.20);
            box-shadow: 0 1px 2px rgba(0,0,0,0.35), 0 8px 22px -14px rgba(0,0,0,0.55);
        }
        .sorenza-req-cta__icon {
            flex: 0 0 auto;
            width: 40px; height: 40px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #8b6914;
            background: rgba(219,197,132,0.16);
            box-shadow: inset 0 0 0 1px rgba(187,161,79,0.25);
        }
        .dark .sorenza-req-cta__icon {
            color: #DBC584;
            background: rgba(187,161,79,0.12);
            box-shadow: inset 0 0 0 1px rgba(187,161,79,0.25);
        }
        .sorenza-req-cta__text { flex: 1 1 auto; min-width: 0; }
        .sorenza-req-cta__title {
            font-weight: 600;
            font-size: 0.95rem;
            color: #111827;
            line-height: 1.3;
        }
        .dark .sorenza-req-cta__title { color: #f3f4f6; }
        .sorenza-req-cta__desc {
            font-size: 0.8rem;
            color: #6b7280;
            line-height: 1.4;
            margin-top: 2px;
        }
        .dark .sorenza-req-cta__desc { color: #9ca3af; }

        .sorenza-req-cta__pending {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-top: 8px;
            font-size: 0.75rem;
            font-weight: 500;
            color: #92400e;
            text-decoration: none;
        }
        .sorenza-req-cta__pending:hover { text-decoration: underline; }
        .dark .sorenza-req-cta__pending { color: #fcd34d; }
        .sorenza-req-cta__dot {
            width: 6px; height: 6px;
            border-radius: 999px;
            background: #f59e0b;
            display: inline-block;
            animation: sorenza-pulse 1.6s ease-in-out infinite;
        }
        @keyframes sorenza-pulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50%      { opacity: 0.45; transform: scale(0.85); }
        }

        .sorenza-req-cta__actions {
            display: flex;
            flex: 0 0 auto;
            align-items: center;
            gap: 8px;
        }
        .sorenza-req-cta__btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 0.5rem 0.9rem;
            border-radius: 0.6rem;
            font-size: 0.85rem;
            font-weight: 600;
            text-decoration: none;
            line-height: 1;
            white-space: nowrap;
            transition: all .15s ease;
            cursor: pointer;
        }
        .sorenza-req-cta__btn--primary {
            background: #111827;
            color: #ffffff;
            box-shadow: 0 1px 2px rgba(0,0,0,0.08), 0 4px 12px -6px rgba(0,0,0,0.35);
        }
        .sorenza-req-cta__btn--primary:hover {
            background: #1f2937;
            color: #ffffff;
            transform: translateY(-1px);
        }
        .dark .sorenza-req-cta__btn--primary {
            background: #ffffff;
            color: #111827;
        }
        .dark .sorenza-req-cta__btn--primary:hover {
            background: #e5e7eb;
            color: #111827;
        }
        .sorenza-req-cta__btn--ghost {
            background: transparent;
            color: #4b5563;
            border: 1px solid rgba(0,0,0,0.08);
        }
        .sorenza-req-cta__btn--ghost:hover {
            background: rgba(0,0,0,0.04);
            color: #111827;
        }
        .dark .sorenza-req-cta__btn--ghost {
            color: #d1d5db;
            border-color: rgba(255,255,255,0.10);
        }
        .dark .sorenza-req-cta__btn--ghost:hover {
            background: rgba(255,255,255,0.06);
            color: #f3f4f6;
        }

        /* Mobile — stack neatly */
        @media (max-width: 640px) {
            .sorenza-req-cta {
                flex-direction: column;
                align-items: stretch;
                padding: 1rem;
            }
            .sorenza-req-cta__actions {
                width: 100%;
                flex-direction: column-reverse;
            }
            .sorenza-req-cta__btn {
                width: 100%;
                justify-content: center;
                padding: 0.65rem 1rem;
                font-size: 0.9rem;
            }
        }
    </style>
</x-filament-widgets::widget>
