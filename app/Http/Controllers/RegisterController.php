<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserLogin; // ใช้โมเดลของตาราง user_login

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register'); // แสดงฟอร์มสมัครสมาชิก
    }

    public function register(Request $request)
    {
        // Validate ข้อมูลก่อนบันทึก
        $validated = $request->validate([
            'user_name' => 'required|unique:user_login,user_name|max:50',
            'password' => 'required|min:6',
            'pre_name' => 'required|max:50',
            'first_name' => 'required|max:100',
            'last_name' => 'required|max:100',
            'user_status' => 'required|max:50',
            'position' => 'nullable|max:100',
            'department' => 'nullable|max:100',
        ], [
            'user_name.required' => 'กรุณากรอกชื่อผู้ใช้',
            'user_name.unique' => 'ชื่อผู้ใช้นี้มีอยู่ในระบบแล้ว',
            'user_name.max' => 'ชื่อผู้ใช้ต้องไม่เกิน 50 ตัวอักษร',
            'password.required' => 'กรุณากรอกรหัสผ่าน',
            'password.min' => 'รหัสผ่านต้องมีอย่างน้อย 6 ตัวอักษร',
            'pre_name.required' => 'กรุณากรอกคำนำหน้า',
            'pre_name.max' => 'คำนำหน้าต้องไม่เกิน 50 ตัวอักษร',
            'first_name.required' => 'กรุณากรอกชื่อจริง',
            'first_name.max' => 'ชื่อจริงต้องไม่เกิน 100 ตัวอักษร',
            'last_name.required' => 'กรุณากรอกนามสกุล',
            'last_name.max' => 'นามสกุลต้องไม่เกิน 100 ตัวอักษร',
            'user_status.required' => 'กรุณาเลือกสถานะผู้ใช้',
            'user_status.max' => 'สถานะผู้ใช้ต้องไม่เกิน 50 ตัวอักษร',
            'position.max' => 'ตำแหน่งต้องไม่เกิน 100 ตัวอักษร',
            'department.max' => 'แผนกต้องไม่เกิน 100 ตัวอักษร',
        ]);

        // การบันทึกข้อมูลลงฐานข้อมูล
        $user = new UserLogin();
        $user->user_name = $validated['user_name'];
        $user->password = bcrypt($validated['password']); // เข้ารหัสรหัสผ่าน
        $user->pre_name = $validated['pre_name'];
        $user->first_name = $validated['first_name'];
        $user->last_name = $validated['last_name'];
        $user->user_status = $validated['user_status'];
        $user->position = $validated['position'];
        $user->department = $validated['department'];
        $user->save(); // บันทึกข้อมูลลงในฐานข้อมูล

        return redirect()->route('login')->with('success', 'สมัครสมาชิกสำเร็จ');
    }
}
