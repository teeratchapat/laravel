<?php

namespace App\Http\Controllers;

use App\Models\Employee;  // เพิ่มการนำเข้า Employee model
use App\Models\LeaveRecord;  // เพิ่มการนำเข้า LeaveRecord model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\DeptCat;


class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::all();
        return view('employees.crud.index', compact('employees'));
    }

    public function create()
    {
        $departments = \DB::table('dep_cate')->get();  // ดึงข้อมูลแผนกจาก dep_cate
        return view('employees.crud.create', compact('departments'));  // ส่งข้อมูลแผนกไปยัง view
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_code' => 'required|unique:employees',
            'full_name' => 'required',
            'position' => 'required',
            'department' => 'required',
            'start_date' => 'required|date',
            'pre_name' => 'nullable',
            'division' => 'nullable',
            'category' => 'nullable',
            'Dep_Category' => 'nullable',
        ]);

        // Create ข้อมูลใหม่และตั้งค่าให้ฟิลด์ที่ไม่ได้กรอกเป็น null
        Employee::create([
            'employee_code' => $request->employee_code,
            'pre_name' => $request->pre_name ?: null, // ถ้าไม่ได้กรอกให้เป็น null
            'full_name' => $request->full_name,
            'position' => $request->position,
            'department' => $request->department,
            'division' => $request->division ?: null, // ถ้าไม่ได้กรอกให้เป็น null
            'category' => $request->category ?: null, // ถ้าไม่ได้กรอกให้เป็น null
            'Dep_Category' => $request->Dep_Category ?: null, // ถ้าไม่ได้กรอกให้เป็น null
            'start_date' => $request->start_date,
        ]);


        return redirect()->route('employees.index')->with('success', 'เพิ่มข้อมูลพนักงานเรียบร้อยแล้ว');
    }

    public function edit($employee_code)
    {
        $employee = Employee::findOrFail($employee_code);
        $departments = DeptCat::all(); // ดึงแผนกทั้งหมด
    
        return view('employees.crud.edit', compact('employee', 'departments'));
    }

    public function update(Request $request, $employee_code)
    {
        $request->validate([
            'full_name' => 'required',
            'position' => 'required',
            'department' => 'required',
            'start_date' => 'required|date',
            'pre_name' => 'nullable',
            'division' => 'nullable',
            'category' => 'nullable',
            'Dep_Category' => 'nullable',
        ]);

        // ค้นหาพนักงานที่ต้องการอัพเดต
        $employee = Employee::findOrFail($employee_code);
        $employee->update([
            'pre_name' => $request->pre_name ?: null, // ถ้าไม่ได้กรอกให้เป็น null
            'full_name' => $request->full_name,
            'position' => $request->position,
            'department' => $request->department,
            'division' => $request->division ?: null, // ถ้าไม่ได้กรอกให้เป็น null
            'category' => $request->category ?: null, // ถ้าไม่ได้กรอกให้เป็น null
            'Dep_Category' => $request->Dep_Category ?: null, // ถ้าไม่ได้กรอกให้เป็น null
            'start_date' => $request->start_date,
        ]);

        return redirect()->route('employees.index')->with('success', 'แก้ไขข้อมูลพนักงานเรียบร้อยแล้ว');
    }

    public function destroy($employee_code)
    {
        $employee = Employee::findOrFail($employee_code);
        $employee->delete();

        return redirect()->route('employees.index')->with('success', 'ลบข้อมูลพนักงานเรียบร้อยแล้ว');
    }
}
