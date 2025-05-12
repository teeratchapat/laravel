<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Carbon\Carbon;
use Carbon\CarbonImmutable;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Carbon::setLocale('th'); // ตั้งค่าภาษาไทย
        CarbonImmutable::setLocale('th'); // สำหรับ Carbon แบบ immutable (ใช้ใน Laravel)
    }
}
