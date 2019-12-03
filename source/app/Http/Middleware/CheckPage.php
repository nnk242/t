<?php

namespace App\Http\Middleware;

use App\Model\UserRolePage;
use Closure;
use Illuminate\Support\Facades\Auth;

class CheckPage
{
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            if (Auth::user()->role === 'admin') {
                return $next($request);
            }
            return abort('404');
        }
        return redirect()->route('login');
    }
}
