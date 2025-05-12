<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Leave; // ใช้โมเดล Leave หรือโมเดลที่เกี่ยวข้องกับข้อมูลการลา

class DashboardController extends Controller
{
    public function index()
    {
        // ดึงข้อมูลจากฐานข้อมูล (ตัวอย่างการดึงข้อมูลการลา)
        $leaveData = Leave::selectRaw('MONTH(created_at) as month, SUM(duration) as total_leave')
            ->groupBy('month')
            ->get();

        // ส่งข้อมูลไปยัง View
        return view('dashboard.index', compact('leaveData'));
    }
}
