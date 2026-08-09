<!doctype html>
<html lang="bs">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Hvala na narudžbi — Sorénza</title>
</head>
<body style="margin:0; padding:0; background:#f7f4ee; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color:#1f2937; -webkit-font-smoothing: antialiased;">

<table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="background:#f7f4ee;">
    <tr>
        <td align="center" style="padding: 24px 12px;">

            <table width="600" cellpadding="0" cellspacing="0" role="presentation" style="max-width:600px; width:100%; background:#ffffff; border-radius:16px; overflow:hidden; box-shadow: 0 8px 30px -12px rgba(139,105,20,0.20);">

                {{-- Gold ribbon --}}
                <tr>
                    <td style="height:6px; background: linear-gradient(90deg, #8b6914 0%, #BBA14F 50%, #DBC584 100%);"></td>
                </tr>

                {{-- Brand --}}
                <tr>
                    <td align="center" style="padding: 28px 24px 8px;">
                        <div style="font-family: Georgia, 'Times New Roman', serif; font-style: italic; font-size: 26px; color:#111827; letter-spacing:0.5px;">
                            Sorénza
                        </div>
                        <div style="font-size:10px; letter-spacing:0.35em; text-transform:uppercase; color:#8b6914; margin-top:2px;">Luksuzni Parfemi</div>
                    </td>
                </tr>

                {{-- Headline --}}
                <tr>
                    <td align="center" style="padding: 8px 32px 8px;">
                        <div style="display:inline-block; width:44px; height:44px; border-radius:999px; background:linear-gradient(135deg,#DBC584,#8b6914); color:#fff; line-height:44px; text-align:center; font-size:22px; margin-bottom:12px;">✓</div>
                        <h1 style="margin:0 0 8px; font-family: Georgia, serif; font-style: italic; font-weight: 400; font-size:26px; color:#111827; line-height:1.2;">
                            Hvala na narudžbi, {{ e(explode(' ', trim($order->full_name))[0] ?? 'kupac') }}!
                        </h1>
                        <p style="margin:0; font-size:14px; color:#6b7280; line-height:1.55;">
                            Vaša narudžba je uspješno primljena. Uskoro ćemo Vas kontaktirati radi potvrde.
                        </p>
                    </td>
                </tr>

                {{-- Order pretty_id + track button --}}
                <tr>
                    <td align="center" style="padding: 24px 32px 8px;">
                        <div style="display:inline-block; background: #faf6ec; border: 1px solid #e6d9b4; border-radius: 12px; padding: 14px 22px;">
                            <div style="font-size:10px; letter-spacing:0.25em; text-transform:uppercase; color:#8b6914; margin-bottom:4px;">Broj narudžbe</div>
                            <div style="font-family: 'Menlo', 'Consolas', monospace; font-size:22px; font-weight:700; color:#111827; letter-spacing: 0.05em;">
                                {{ $order->pretty_id }}
                            </div>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td align="center" style="padding: 16px 32px 8px;">
                        <a href="{{ $trackUrl }}"
                           style="display:inline-block; background: linear-gradient(90deg, #8b6914, #BBA14F, #DBC584); color:#ffffff; text-decoration:none; font-weight:700; font-size:13px; letter-spacing:0.15em; text-transform:uppercase; padding: 14px 28px; border-radius: 999px;">
                            Prati narudžbu
                        </a>
                    </td>
                </tr>

                {{-- Items table --}}
                <tr>
                    <td style="padding: 24px 32px 0;">
                        <div style="font-size:10px; letter-spacing:0.25em; text-transform:uppercase; color:#8b6914; font-weight:600; margin-bottom:8px;">Artikli</div>

                        <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="border-collapse: collapse;">
                            @foreach($order->perfumes as $item)
                                <tr>
                                    <td style="padding: 12px 0; border-bottom: 1px solid #f0e6cb;">
                                        <div style="font-weight:600; color:#111827; font-size:14px;">{{ $item->name }}</div>
                                        @if(! empty($item->inspired_by))
                                            <div style="font-size:12px; color:#6b7280; font-style: italic;">Inspirisano od: {{ $item->inspired_by }}</div>
                                        @endif
                                    </td>
                                    <td align="right" style="padding: 12px 0; border-bottom: 1px solid #f0e6cb; white-space: nowrap; color:#6b7280; font-size:13px;">
                                        {{ (int) $item->pivot->quantity }} × {{ number_format($item->pivot->price, 2, ',', '.') }} KM
                                    </td>
                                    <td align="right" style="padding: 12px 0 12px 12px; border-bottom: 1px solid #f0e6cb; white-space: nowrap; font-weight:700; color:#111827; font-size:13px;">
                                        {{ number_format($item->pivot->price * $item->pivot->quantity, 2, ',', '.') }} KM
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </td>
                </tr>

                {{-- Totals --}}
                <tr>
                    <td style="padding: 8px 32px 4px;">
                        <table width="100%" cellpadding="0" cellspacing="0" role="presentation">
                            <tr>
                                <td style="padding:6px 0; font-size:13px; color:#6b7280;">Iznos artikala</td>
                                <td align="right" style="padding:6px 0; font-size:13px; color:#111827; font-weight:600;">{{ number_format($order->subtotal, 2, ',', '.') }} KM</td>
                            </tr>
                            @if($order->discount_amount > 0)
                                <tr>
                                    <td style="padding:6px 0; font-size:13px; color:#059669;">Popust @if($order->coupon_code) ({{ $order->coupon_code }}) @endif</td>
                                    <td align="right" style="padding:6px 0; font-size:13px; color:#059669; font-weight:600;">− {{ number_format($order->discount_amount, 2, ',', '.') }} KM</td>
                                </tr>
                            @endif
                            <tr>
                                <td style="padding:6px 0; font-size:13px; color:#6b7280;">Dostava</td>
                                <td align="right" style="padding:6px 0; font-size:13px; color:#111827; font-weight:600;">
                                    @if($order->shipping_fee == 0)
                                        <span style="color:#059669;">Besplatno</span>
                                    @else
                                        {{ number_format($order->shipping_fee, 2, ',', '.') }} KM
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="padding-top:8px; border-top: 2px solid #f0e6cb;"></td>
                            </tr>
                            <tr>
                                <td style="padding:12px 0 4px; font-size:12px; letter-spacing:0.18em; text-transform:uppercase; color:#6b7280; font-weight:700;">Ukupno</td>
                                <td align="right" style="padding:12px 0 4px; font-family: Georgia, serif; font-size: 22px; color:#8b6914; font-weight:700;">
                                    {{ number_format($order->amount, 2, ',', '.') }} KM
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                {{-- Delivery info --}}
                <tr>
                    <td style="padding: 24px 32px 8px;">
                        <div style="font-size:10px; letter-spacing:0.25em; text-transform:uppercase; color:#8b6914; font-weight:600; margin-bottom:10px;">Podaci za dostavu</div>
                        <div style="font-size:13px; line-height:1.7; color:#374151;">
                            {{ $order->full_name }}<br>
                            {{ $order->address_line_1 }}@if($order->address_line_2), {{ $order->address_line_2 }}@endif<br>
                            {{ $order->zipcode }} {{ $order->city }}@if($order->canton), {{ $order->canton?->value ?? $order->canton }}@endif<br>
                            📞 {{ $order->pretty_phone ?: $order->phone }}
                        </div>
                    </td>
                </tr>

                {{-- COD notice --}}
                <tr>
                    <td style="padding: 20px 32px 4px;">
                        <div style="background:#faf6ec; border:1px solid #e6d9b4; border-radius:10px; padding:12px 14px; font-size:12px; color:#78350f; line-height:1.5;">
                            💳 <strong>Plaćanje pouzećem</strong> — narudžbu plaćate kuriru prilikom preuzimanja.
                        </div>
                    </td>
                </tr>

                {{-- Footer --}}
                <tr>
                    <td align="center" style="padding: 28px 32px 32px;">
                        <div style="font-size:12px; color:#6b7280; line-height:1.6;">
                            Hvala što ste odabrali <strong>Sorénza</strong> ✨<br>
                            Za sva pitanja: <a href="mailto:info@sorenzaperfumes.com" style="color:#8b6914; text-decoration:none;">info@sorenzaperfumes.com</a>
                        </div>
                        <div style="margin-top: 16px; font-size:10px; color:#9ca3af; letter-spacing:0.15em; text-transform:uppercase;">
                            Sorénza · Luksuzni parfemi · BiH
                        </div>
                    </td>
                </tr>
            </table>

            <div style="max-width:600px; padding: 14px 12px 0; font-size:11px; color:#9ca3af; text-align:center;">
                Ovaj email ste primili jer ste postavili narudžbu na sorenzaperfumes.com.
            </div>

        </td>
    </tr>
</table>

</body>
</html>
