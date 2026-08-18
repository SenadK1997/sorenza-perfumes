<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Prijava — Sorénza</title>
</head>
<body style="margin:0; padding:0; background:#f6f5f2; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color:#111827;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f6f5f2; padding:24px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="560" cellpadding="0" cellspacing="0" style="background:#ffffff; border-radius:16px; overflow:hidden; box-shadow:0 6px 24px -8px rgba(0,0,0,0.08);">
                    <tr>
                        <td style="height:6px; background:linear-gradient(90deg,#8b6914,#BBA14F,#DBC584);"></td>
                    </tr>
                    <tr>
                        <td style="padding:32px 32px 8px 32px;">
                            <div style="font-size:11px; letter-spacing:4px; text-transform:uppercase; color:#8b6914; margin-bottom:12px;">Sorénza</div>
                            <h1 style="margin:0 0 12px 0; font-size:22px; font-weight:600; color:#111827;">Prijavite se na svoj nalog</h1>
                            <p style="margin:0 0 20px 0; font-size:15px; line-height:1.55; color:#4b5563;">
                                Zdravo{{ $customer->full_name ? ', ' . $customer->full_name : '' }} — kliknite na dugme ispod da se prijavite na svoj Sorénza nalog. Link vrijedi <strong>{{ $ttl }} minuta</strong> i može se iskoristiti samo jednom.
                            </p>
                            <p style="margin:24px 0;">
                                <a href="{{ $url }}" style="display:inline-block; background:linear-gradient(90deg,#8b6914,#BBA14F,#DBC584); color:#ffffff; text-decoration:none; font-weight:600; font-size:14px; padding:12px 22px; border-radius:999px; letter-spacing:1px; text-transform:uppercase;">
                                    Prijavi me
                                </a>
                            </p>
                            <p style="margin:16px 0 4px 0; font-size:12px; color:#6b7280;">
                                Ako dugme ne radi, kopirajte ovaj link u pretraživač:
                            </p>
                            <p style="margin:0 0 24px 0; font-size:12px; word-break:break-all; color:#374151;">
                                {{ $url }}
                            </p>
                            <hr style="border:none; border-top:1px solid #e5e7eb; margin:24px 0;">
                            <p style="margin:0; font-size:12px; color:#9ca3af; line-height:1.6;">
                                Niste tražili ovu prijavu? Slobodno zanemarite ovaj email — nalog vam neće biti otvoren.
                            </p>
                        </td>
                    </tr>
                </table>
                <p style="margin-top:16px; font-size:11px; color:#9ca3af;">© {{ date('Y') }} Sorénza Parfumes</p>
            </td>
        </tr>
    </table>
</body>
</html>
