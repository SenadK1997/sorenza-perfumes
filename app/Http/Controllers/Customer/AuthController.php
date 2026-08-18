<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\BlockedEmail;
use App\Models\BlockedIp;
use App\Models\Customer;
use App\Services\CustomerMagicLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;

class AuthController extends Controller
{
    /**
     * Request a magic link. Always returns the same success flash to avoid
     * leaking whether an email exists / is eligible.
     */
    public function sendMagicLink(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email|max:255',
        ]);

        $email = strtolower(trim($data['email']));
        $ip    = $request->ip();
        $ua    = (string) $request->userAgent();

        // IP or email hard-block — silently drop
        if (BlockedIp::isBlocked($ip) || BlockedEmail::isBlocked($email)) {
            return back()->with('status', 'Molimo pokušajte kasnije.');
        }

        // Rate-limit before doing any DB lookups (protects against email enumeration flood)
        if ($msg = CustomerMagicLink::throttleMessage($email, $ip)) {
            return back()->withInput()->withErrors(['email' => $msg]);
        }

        CustomerMagicLink::recordAttempt($email, $ip);

        $customer = Customer::whereRaw('LOWER(email) = ?', [$email])->first();

        if (!$customer) {
            return back()->withInput()->withErrors([
                'email' => 'Ova email adresa nije registrirana kod nas. Nalog se otvara tek nakon prve narudžbe.',
            ]);
        }

        if ($customer->is_blocked) {
            // Silent: don't reveal block state, just show a soft message.
            return back()->with('status', 'Trenutno nije moguće poslati link. Molimo kontaktirajte podršku.');
        }

        try {
            CustomerMagicLink::send($customer, $ip, $ua);
        } catch (\Throwable $e) {
            Log::warning('Magic link send failed: ' . $e->getMessage());
        }

        return back()->with('status', 'Poslali smo vam link za prijavu na ' . $customer->email . '. Provjerite inbox (i spam) u narednih ' . CustomerMagicLink::TOKEN_TTL_MINUTES . ' minuta.');
    }

    /**
     * Consume the magic link and log the customer in.
     */
    public function consumeMagicLink(Request $request, string $token)
    {
        $auth = CustomerMagicLink::findUsable($token);

        if (!$auth) {
            return redirect()->route('customer.login')->withErrors([
                'email' => 'Link je nevažeći ili je istekao. Zatražite novi.',
            ]);
        }

        $customer = $auth->customer;
        if (!$customer || $customer->is_blocked) {
            return redirect()->route('customer.login')->withErrors([
                'email' => 'Ovaj nalog trenutno nije dostupan.',
            ]);
        }

        $auth->update(['consumed_at' => now()]);

        Auth::guard('customer')->login($customer, remember: true);
        $customer->update(['last_login_at' => now()]);

        $request->session()->regenerate();

        return redirect()->route('customer.dashboard');
    }

    /**
     * Optional password login.
     */
    public function passwordLogin(Request $request)
    {
        $data = $request->validate([
            'email'    => 'required|email|max:255',
            'password' => 'required|string|max:255',
        ]);

        $email = strtolower(trim($data['email']));
        $ip    = $request->ip();

        if (BlockedIp::isBlocked($ip) || BlockedEmail::isBlocked($email)) {
            return back()->withErrors([
                'email' => 'Trenutno nije moguće prijaviti se.',
            ]);
        }

        $lockKey = 'customer-pw-login:' . $ip . '|' . $email;
        if (RateLimiter::tooManyAttempts($lockKey, 5)) {
            $sec = RateLimiter::availableIn($lockKey);
            return back()->withInput()->withErrors([
                'email' => 'Previše neuspješnih pokušaja. Pokušajte za ' . ceil($sec / 60) . ' min.',
            ]);
        }

        $customer = Customer::whereRaw('LOWER(email) = ?', [$email])->first();

        if (!$customer || !$customer->password || !Hash::check($data['password'], $customer->password) || $customer->is_blocked) {
            RateLimiter::hit($lockKey, 600);
            return back()->withInput()->withErrors([
                'email' => 'Pogrešan email ili lozinka.',
            ]);
        }

        RateLimiter::clear($lockKey);

        Auth::guard('customer')->login($customer, remember: true);
        $customer->update(['last_login_at' => now()]);
        $request->session()->regenerate();

        return redirect()->route('customer.dashboard');
    }

    public function logout(Request $request)
    {
        Auth::guard('customer')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
