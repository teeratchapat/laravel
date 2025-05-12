<?php

// namespace App\Http\Controllers;

// use Illuminate\Http\Request;
// use App\Models\Employee;
// use App\Models\History;
// class LeaveController extends Controller
// {
//     public function index()
//     {
//         $employees = Employee::all(); // ดึงข้อมูลพนักงานทั้งหมด

//         $histories = History::with('employee')->get(); // ดึงข้อมูลการลา พร้อมข้อมูลพนักงาน
//         return view('employees.history_leave', compact('employees', 'histories'));
//     }

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class LeaveController extends Controller
{
    public function index(Request $request)
    {
        $search    = $request->input('search');    // }
        $month     = $request->input('month');     // รับค่าค้นหาจาก request
        $leaveType = $request->input('leaveType'); // รับค่าเดือน

        // ประเภทการลา
        $employees = DB::table('employees')
            ->leftJoin('history', 'employees.employee_code', '=', 'history.employee_code') // JOIN ตาราง employees และ history โดยใช้ employee_code เป็นตัวเชื่อม
            ->select(
                'employees.employee_code',
                'employees.pre_name',
                'employees.full_name',
                'employees.category',
                'employees.position',
                'employees.department',
                'employees.division',
                'employees.start_date',
                // เชื่อมกันที่ employee_code
                'history.personal_leave',
                'history.vacation_leave',
                'history.sick_leave',
                'history.absent_days',
                'history.late_days',
                'history.leave_total'

                // //แก้ไข sum ข้อมูลการลา ให้บวกไปเรื่อยๆแล้วแต่ละเดือน
                // DB::raw('COALESCE(SUM(history.personal_leave), 0) AS personal_leave'),
                // DB::raw('COALESCE(SUM(history.vacation_leave), 0) AS vacation_leave'),
                // DB::raw('COALESCE(SUM(history.sick_leave), 0) AS sick_leave'),
                // DB::raw('COALESCE(SUM(history.absent_days), 0) AS absent_days'),
                // DB::raw('COALESCE(SUM(history.late_days), 0) AS late_days'),
                // DB::raw('COALESCE(SUM(history.leave_total), 0) AS leave_total')

            )

            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('employees.employee_code', 'LIKE', "%$search%")
                        ->orWhere(DB::raw("CONCAT(employees.pre_name, employees.full_name)"), 'LIKE', "%$search%")
                        ->orWhere('employees.department', 'LIKE', "%$search%")
                        ->orWhere('employees.position', 'LIKE', "%$search%");
                });
            })
            ->when($month, function ($query, $month) {
                return $query->whereMonth('history.import_month_year', '=', date('m', strtotime($month)))
                    ->whereYear('history.import_month_year', '=', date('Y', strtotime($month)));
            })
            ->when($leaveType, function ($query, $leaveType) {
                return $query->where("history.$leaveType", '>', 0);
            })
            //groupby ตาม employee_code
            // ->groupBy(
            //     'employees.employee_code',
            //     'employees.pre_name',
            //     'employees.full_name',
            //     'employees.category',
            //     'employees.position',
            //     'employees.department',
            //     'employees.division',
            //     'employees.start_date'
            // )
            ->get();

        if ($request->ajax()) {
            return response()->json(['employees' => $employees]);
        }

        return view('employees.history_leave', compact('employees'));
    }

    public function Insert_employees()
    {
        return view('employees.import_leave');
    }

}
