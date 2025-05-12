<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\History;

class HomeController extends Controller
{
    public function index()
    {
        $employees = Employee::all(); // ดึงข้อมูลพนักงานทั้งหมด
        $histories = History::with('employee')->get(); // ดึงข้อมูลการลา พร้อมข้อมูลพนักงาน

        return view('home', compact('employees', 'histories'));
    }
}
