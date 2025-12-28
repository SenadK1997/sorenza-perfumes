<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfNotAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();

            // If user is seller and tries to access any admin route
            if ($user->hasRole('seller') && $request->is('admin*')) {
                return redirect('/seller');
            }
        }

        return $next($request);
    }

}

