<?php

namespace App\Http\Middleware;

use App\Model\UserRolePage;
use Closure;
use Illuminate\Support\Facades\Auth;

class CheckUserRole
{
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            $page_selected = Auth::user()->getPageSelected();
            if (Auth::user()->role === 'admin') {
                return $next($request);
            } elseif ($page_selected === $request->fb_page_id) {
                return $next($request);
            }
            return abort('404');
        }
        return redirect()->route('login');
    }
}
