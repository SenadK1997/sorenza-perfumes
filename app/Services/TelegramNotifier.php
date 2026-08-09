<?php

namespace App\Services;

use App\Models\Order;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramNotifier
{
    protected const API = 'https://api.telegram.org';

    public static function enabled(): bool
    {
        return SiteSetting::get('telegram_bot_token') && SiteSetting::get('telegram_chat_id');
    }

    /**
     * Send a plain text (Markdown) message. Returns true on success, false on failure.
     */
    public static function send(string $text): bool
    {
        if (! self::enabled()) return false;

        $token  = SiteSetting::get('telegram_bot_token');
        $chatId = SiteSetting::get('telegram_chat_id');

        try {
            $res = Http::timeout(4)->post(self::API . "/bot{$token}/sendMessage", [
                'chat_id'                  => $chatId,
                'text'                     => $text,
                'parse_mode'               => 'Markdown',
                'disable_web_page_preview' => true,
            ]);

            if (! $res->successful()) {
                Log::warning('Telegram send failed: ' . $res->status() . ' ' . $res->body());
                return false;
            }
            return true;
        } catch (\Throwable $e) {
            Log::warning('Telegram send exception: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send a formatted "new order" message.
     */
    public static function orderPlaced(Order $order): bool
    {
        $order->loadMissing('perfumes');

        $lines = [];
        foreach ($order->perfumes as $p) {
            $lines[] = "• {$p->name} × " . (int) $p->pivot->quantity;
        }
        $items = implode("\n", $lines) ?: '—';

        $trackUrl = route('order.track', ['pretty_id' => $order->pretty_id]);

        $text = "🛍️ *Nova narudžba na Sorénza!*\n"
              . "━━━━━━━━━━━━━━━━━━━━\n"
              . "🆔 *#{$order->pretty_id}*\n"
              . "👤 " . self::escape($order->full_name) . "\n"
              . "📍 " . self::escape($order->city) . "\n"
              . "📞 " . self::escape((string) $order->phone) . "\n"
              . "💰 *" . number_format((float) $order->amount, 2, ',', '.') . " KM*\n"
              . "━━━━━━━━━━━━━━━━━━━━\n"
              . $items . "\n"
              . "━━━━━━━━━━━━━━━━━━━━\n"
              . "🔗 [Prati narudžbu]({$trackUrl})";

        return self::send($text);
    }

    /** Escape Markdown special chars in user-provided text (Telegram legacy Markdown). */
    protected static function escape(string $s): string
    {
        return str_replace(['_', '*', '`', '['], ['\\_', '\\*', '\\`', '\\['], $s);
    }
}
