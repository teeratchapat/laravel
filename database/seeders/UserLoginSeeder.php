<?php

// namespace Database\Seeders;

// use Illuminate\Database\Seeder;
// use Illuminate\Support\Facades\DB;
// use Illuminate\Support\Facades\Hash;

// class UserLoginSeeder extends Seeder
// {
//     public function run(): void
//     {
//         DB::table('user_login')->insert([
//             'user_name' => 'admin',
//             'password' => Hash::make('password123'), // เข้ารหัสรหัสผ่าน
//             'pre_name' => 'Mr.',
//             'first_name' => 'Admin',
//             'last_name' => 'User',
//             'user_status' => 'admin',
//             'position' => 'Manager',
//             'department' => 'IT',
//         ]);
//     }
// }

use Illuminate\Support\Facades\Hash;
use App\Models\UserLogin; // อย่าลืมนำเข้า Model

UserLogin::create([
    'user_name' => 'admin',
    'password' => Hash::make('123456'),
    'pre_name' => 'นาย', // 👈 เพิ่มเข้าไป
    'first_name' => 'Admin',
    'last_name' => 'User',
    'user_status' => 'admin',
    'position' => 'Manager',
    'department' => 'IT',
]);

$request->validate([
    'pre_name' => 'required|string|max:25',
]);
