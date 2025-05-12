<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    protected $except = [
        '/api/*',  // ✅ ไม่ต้องเช็ค CSRF กับ API
        '/login',  // ✅ ถ้าไม่อยากให้หน้า Login เช็ค CSRF
    ];
}
