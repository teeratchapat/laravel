<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // แสดงหน้า Login
    public function showLoginForm()
    {
        return view('auth.login'); // แสดงหน้า Login
    }

    public function login(Request $request)
    {
        //เทส
        // ตรวจสอบการกรอกข้อมูล
        $credentials = $request->validate([
            'username' => 'required|string', // ชื่อผู้ใช้ต้องไม่ว่าง
            'password' => 'required|string', // รหัสผ่านต้องไม่ว่าง
        ]);

        // ตรวจสอบข้อมูลผู้ใช้ ถ้าข้อมูลถูกต้องจะทำการเข้าสู่ระบบ
        if (Auth::attempt(['user_name' => $credentials['username'], 'password' => $credentials['password']])) {
            // ถ้าเข้าสู่ระบบสำเร็จ เปลี่ยนเส้นทางไปหน้า home
            return redirect()->route('home');
        }

        // ถ้าเข้าสู่ระบบไม่สำเร็จ ให้กลับไปที่หน้า login พร้อมกับข้อความ error
        return back()->withErrors(['login' => 'ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง'])
                     ->withInput(); // ส่งข้อมูลที่กรอกไปยังหน้า login
    }

    // การออกจากระบบ
    public function logout()
    {
        Auth::logout(); // ออกจากระบบ
        return redirect()->route('login'); // เปลี่ยนเส้นทางกลับไปที่หน้า login
    }
}
