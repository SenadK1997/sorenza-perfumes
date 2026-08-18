<?php

namespace App\Services;

use App\Mail\CustomerMagicLink as CustomerMagicLinkMail;
use App\Models\Customer;
use App\Models\CustomerAuthToken;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class CustomerMagicLink
{
    // Anti-spam limits (per hour)
    public const PER_EMAIL_LIMIT   = 3;
    public const PER_IP_LIMIT      = 10;
    public const COOLDOWN_SECONDS  = 60;   // min seconds between two sends for same email
    public const TOKEN_TTL_MINUTES = 15;

    public static function send(Customer $customer, string $ip, ?string $userAgent): CustomerAuthToken
    {
        $plain = Str::random(48);
        $hash  = hash('sha256', $plain);

        $token = CustomerAuthToken::create([
            'customer_id' => $customer->id,
            'email'       => $customer->email,
            'token_hash'  => $hash,
            'expires_at'  => Carbon::now()->addMinutes(self::TOKEN_TTL_MINUTES),
            'ip'          => $ip,
            'user_agent'  => $userAgent,
        ]);

        Mail::to($customer->email)->send(new CustomerMagicLinkMail($customer, $plain));

        return $token;
    }

    /**
     * Look up an unconsumed, unexpired token by the plain token string.
     */
    public static function findUsable(string $plainToken): ?CustomerAuthToken
    {
        $hash = hash('sha256', $plainToken);

        $token = CustomerAuthToken::where('token_hash', $hash)->first();
        if (!$token || !$token->isUsable()) return null;

        return $token;
    }

    /**
     * Returns null if the customer may receive a new link,
     * or a string message describing why they can't.
     */
    public static function throttleMessage(string $email, string $ip): ?string
    {
        if (RateLimiter::tooManyAttempts(self::emailKey($email), self::PER_EMAIL_LIMIT)) {
            $sec = RateLimiter::availableIn(self::emailKey($email));
            return 'Previše zahtjeva za ovu email adresu. Pokušajte za ' . ceil($sec / 60) . ' min.';
        }

        if (RateLimiter::tooManyAttempts(self::ipKey($ip), self::PER_IP_LIMIT)) {
            $sec = RateLimiter::availableIn(self::ipKey($ip));
            return 'Previše zahtjeva sa ove mreže. Pokušajte za ' . ceil($sec / 60) . ' min.';
        }

        // Cooldown between two consecutive sends for the same email
        $last = CustomerAuthToken::where('email', $email)
            ->latest('id')
            ->first();

        if ($last && $last->created_at->gt(now()->subSeconds(self::COOLDOWN_SECONDS))) {
            $wait = self::COOLDOWN_SECONDS - now()->diffInSeconds($last->created_at);
            return 'Molimo sačekajte ' . max(1, $wait) . ' sekundi prije novog zahtjeva.';
        }

        return null;
    }

    public static function recordAttempt(string $email, string $ip): void
    {
        RateLimiter::hit(self::emailKey($email), 3600);
        RateLimiter::hit(self::ipKey($ip), 3600);
    }

    private static function emailKey(string $email): string
    {
        return 'magic-link:email:' . strtolower(trim($email));
    }

    private static function ipKey(string $ip): string
    {
        return 'magic-link:ip:' . $ip;
    }
}
