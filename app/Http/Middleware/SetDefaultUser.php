<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SetDefaultUser
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            $user = User::first();
            if ($user) {
                Auth::login($user);
            }
        }

        return $next($request);
    }
}
