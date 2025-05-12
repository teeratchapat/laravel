<?php
namespace App\Http\Controllers;

use App\Exports\LeaveReportExport;
use App\Models\DeptCat;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // ใช้สำหรับการส่งออก Excel
use Maatwebsite\Excel\Facades\Excel;

//ใช้ excel Facade

class ReportLeaveHisController extends Controller
{
    // ฟังก์ชันแสดงหน้า Report Leave History
    public function index(Request $request)
    {
        // ดึงข้อมูลแผนกทั้งหมดจากตาราง dep_cates
        $depCates = DeptCat::all();

        // รับค่าเดือน-ปี (ถ้าไม่มีให้ใช้ค่าเดือนปัจจุบัน)
        $monthYear = $request->input('month_year_hidden', Carbon::now()->format('Y-m'));
        $startDate = Carbon::parse($monthYear . '-01')->startOfMonth()->toDateTimeString();
        $endDate   = Carbon::parse($monthYear . '-01')->endOfMonth()->toDateTimeString();

        // รับค่าแผนกและการเช็คพนักงาน
        $depCategory   = $request->input('dep_category', '');
        $checkEmployee = $request->input('check_employee', '');

        // สร้างคำสั่ง SQL ด้วย LEFT JOIN กับ history และ dep_cate
        $query = DB::table('employees as em')
            ->leftJoin('history as his', function ($join) use ($startDate, $endDate) {
                $join->on('em.employee_code', '=', 'his.employee_code')
                    ->whereBetween('his.import_month_year', [$startDate, $endDate]); // กรองตามเดือนที่เลือก
            })
            ->leftJoin('dep_cate', 'em.Dep_Category', '=', 'dep_cate.dep_cate_id')
            ->select(
                'em.employee_code',
                DB::raw("CONCAT(em.pre_name, ' ', em.full_name) AS name"),
                'em.category',
                'dep_cate.dep_cate_name',
                DB::raw('COALESCE(SUM(his.personal_leave), 0) as personal_leave'),
                DB::raw('COALESCE(SUM(his.vacation_leave), 0) as vacation_leave'),
                DB::raw('COALESCE(SUM(his.sick_leave), 0) as sick_leave'),
                DB::raw('COALESCE(SUM(his.absent_days), 0) as absent_days'),
                DB::raw('COALESCE(SUM(his.late_days), 0) as late_days'),
                DB::raw('COALESCE(SUM(his.leave_total), 0) as leave_total')
            )
            ->groupBy('em.employee_code', 'em.pre_name', 'em.full_name', 'em.category', 'dep_cate.dep_cate_name');

        // ฟิลเตอร์ตามแผนก (ถ้ามีการเลือก)
        if ($depCategory) {
            $query->where('em.Dep_Category', $depCategory);
        }

        // ฟิลเตอร์ตามการเช็คพนักงาน (ขาดเกิน 3 ครั้ง หรือสายเกิน 3 ครั้ง)
        if ($checkEmployee === 'absent') {
            $query->having('absent_days', '>', 3); // ฟิลเตอร์ขาดเกิน 3 ครั้ง
        } elseif ($checkEmployee === 'late') {
            $query->having('late_days', '>', 3); // ฟิลเตอร์สายเกิน 3 ครั้ง
        }

        // ดึงข้อมูลจากคิวรี
        $reportData = $query->get();

        // ดึงข้อมูลแผนก
        $depCategories = DB::table('dep_cate')
            ->select('dep_cate_id', 'dep_cate_name')
            ->distinct()
            ->get();

        // ส่งข้อมูลไปยัง View พร้อมค่าที่ผู้ใช้กรอกไว้
        return view('Report.Report_LeaveHis', compact('reportData', 'monthYear', 'depCategories', 'depCategory', 'checkEmployee'));
    }

    // ฟังก์ชัน export Excel
//     public function exportReport(Request $request)
//     {
//         $depCategory   = $request->input('dep_category', '');
//         $checkEmployee = $request->input('check_employee', '');
//         $monthYear     = $request->input('month_year_hidden', Carbon::now()->format('Y-m'));
//         $startDate     = Carbon::parse($monthYear . '-01')->startOfMonth()->toDateTimeString();
//         $endDate       = Carbon::parse($monthYear . '-01')->endOfMonth()->toDateTimeString();

//         // คิวรีข้อมูลการลา
//         $query = DB::table('employees as em')
//             ->leftJoin('history as his', 'em.employee_code', '=', 'his.employee_code')
//             ->leftJoin('dep_cate', 'em.Dep_Category', '=', 'dep_cate.dep_cate_id')
//             ->whereBetween('his.import_month_year', [$startDate, $endDate])
//             ->select(
//                 // 'em.category',
//                 // 'em.employee_code',
//                 // 'dep_cate.dep_cate_name',
//                 // DB::raw("CONCAT(em.pre_name, ' ', em.full_name) AS name"),
//                 // DB::raw('COALESCE(SUM(his.personal_leave), 0) as personal_leave'),
//                 // DB::raw('COALESCE(SUM(his.vacation_leave), 0) as vacation_leave'),
//                 // DB::raw('COALESCE(SUM(his.sick_leave), 0) as sick_leave'),
//                 // DB::raw('COALESCE(SUM(his.absent_days), 0) as absent_days'),
//                 // DB::raw('COALESCE(SUM(his.late_days), 0) as late_days'),
//                 // DB::raw('COALESCE(SUM(his.leave_total), 0) as leave_total')
//                 'em.category',
//                 'em.employee_code',
//                 DB::raw("CONCAT(em.pre_name, ' ', em.full_name) AS name"),
//                 'dep_cate.dep_cate_name',
//                 DB::raw('COALESCE(SUM(his.personal_leave), 0) as personal_leave'),
//                 DB::raw('COALESCE(SUM(his.vacation_leave), 0) as vacation_leave'),
//                 DB::raw('COALESCE(SUM(his.sick_leave), 0) as sick_leave'),
//                 DB::raw('COALESCE(SUM(his.absent_days), 0) as absent_days'),
//                 DB::raw('COALESCE(SUM(his.late_days), 0) as late_days'),
//                 DB::raw('COALESCE(SUM(his.leave_total), 0) as leave_total')
//             )
//             ->groupBy('em.category', 'em.employee_code', 'em.pre_name', 'em.full_name', 'em.Dep_Category', 'dep_cate.dep_cate_name');

//         // ฟิลเตอร์ตามเดือน-ปี
//         if ($monthYear) {
//             $query->where('his.import_month_year', $monthYear . '-01');
//         }

//         if ($depCategory) {
//             $query->where('em.Dep_Category', $depCategory);
//         }

//         // เรียงลำดับตาม category เพื่อให้การจัดกลุ่มถูกต้อง
//         if ($checkEmployee === 'absent') {
//             $query->having('absent_days', '>', 3);
//         } elseif ($checkEmployee === 'late') {
//             $query->having('late_days', '>', 3);
//         }

//         // ดึงข้อมูล เพื่อส่งออกเป็น Excel
//         $reportData_xlsx = $query->get();

//         // ใช้ Export class เพื่อสร้างไฟล์ Excel
//         return Excel::download(new LeaveReportExport($reportData_xlsx), 'รายงานการลา.xlsx');
//     }

    public function exportReport(Request $request)
    {
        $depCategory   = $request->input('dep_category', '');
        $checkEmployee = $request->input('check_employee', '');
        $monthYear     = $request->input('month_year_hidden', Carbon::now()->format('Y-m'));
        $startDate     = Carbon::parse($monthYear . '-01')->startOfMonth()->toDateTimeString();
        $endDate       = Carbon::parse($monthYear . '-01')->endOfMonth()->toDateTimeString();

        // คิวรีข้อมูลการลา
        $query = DB::table('employees as em')
            ->leftJoin('history as his', function ($join) use ($startDate, $endDate) {
                $join->on('em.employee_code', '=', 'his.employee_code')
                    ->whereBetween('his.import_month_year', [$startDate, $endDate]);
            })
            ->leftJoin('dep_cate', 'em.Dep_Category', '=', 'dep_cate.dep_cate_id')
            ->select(
                'em.employee_code',
                DB::raw("CONCAT(em.pre_name, ' ', em.full_name) AS name"),
                'em.category',
                'dep_cate.dep_cate_name',
                DB::raw('COALESCE(SUM(his.personal_leave), 0) as personal_leave'),
                DB::raw('COALESCE(SUM(his.vacation_leave), 0) as vacation_leave'),
                DB::raw('COALESCE(SUM(his.sick_leave), 0) as sick_leave'),
                DB::raw('COALESCE(SUM(his.absent_days), 0) as absent_days'),
                DB::raw('COALESCE(SUM(his.late_days), 0) as late_days'),
                DB::raw('COALESCE(SUM(his.leave_total), 0) as leave_total')
            )
            ->groupBy('em.employee_code', 'em.pre_name', 'em.full_name', 'em.category', 'dep_cate.dep_cate_name');

        // ฟิลเตอร์ตามแผนก (ถ้ามีการเลือก)
        if ($depCategory) {
            $query->where('em.Dep_Category', $depCategory);
        }

        // ฟิลเตอร์ตามการเช็คพนักงาน (ขาดเกิน 3 ครั้ง หรือสายเกิน 3 ครั้ง)
        if ($checkEmployee === 'absent') {
            $query->having('absent_days', '>', 3); // ฟิลเตอร์ขาดเกิน 3 ครั้ง
        } elseif ($checkEmployee === 'late') {
            $query->having('late_days', '>', 3); // ฟิลเตอร์สายเกิน 3 ครั้ง
        }

        // ดึงข้อมูลจากคิวรี
        $reportData = $query->get();

        // ดึงข้อมูลแผนก
        $depCategories = DB::table('dep_cate')
            ->select('dep_cate_id', 'dep_cate_name')
            ->distinct()
            ->get();

        // ส่งข้อมูลไปยัง View พร้อมค่าที่ผู้ใช้กรอกไว้
        return Excel::download(new LeaveReportExport($reportData), 'รายงานการลา.xlsx');
    }

}
