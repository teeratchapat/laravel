<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, $guard = null)
    {
        if (Auth::check()) {
            return redirect('/dashboard'); // เปลี่ยนเส้นทางไปหน้า dashboard หรือที่ต้องการ
        }

        return $next($request);
    }
}
