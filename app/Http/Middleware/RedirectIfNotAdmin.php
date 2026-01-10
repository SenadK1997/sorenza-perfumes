<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RedirectIfNotAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();

            // 1. If they are an admin, ALWAYS allow them to continue
            if ($user->hasRole('admin')) {
                return $next($request);
            }

            // 2. If they are a seller (and we already know they ARE NOT admin)
            // and they try to go to /admin, send them to /seller
            if ($user->hasRole('seller') && $request->is('admin*')) {
                return redirect('/seller');
            }
        }

        return $next($request);
    }

}

