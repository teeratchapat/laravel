<?php
namespace App\Http\Controllers;

use App\Models\DeptCat;
use App\Models\Employee;
use App\Models\History;
use Illuminate\Http\Request;

//เอาฟังชั่น job เข้ามาช่วยในการเก็บการ import ข้อมูล excel
use Maatwebsite\Excel\Facades\Excel;
use Session;

class ImportController extends Controller
{
    //show form ตัวเก่าที่ใช้งานได้
    public function showForm()
    {
                                                               // ดึงข้อมูลจาก session ถ้ามี
        $sheetNames      = session('sheet_names', []);         // ชื่อ Sheet ที่อ่านได้จาก Excel
        $importedData    = session('imported_data', []);       // ข้อมูลที่นำเข้าจาก Excel
        $importMonthYear = session('import_month_year', null); // เดือนและปีที่เลือก

                                    // ดึงข้อมูลจาก dep_cates
        $depCates = DeptCat::all(); // ดึงข้อมูลทั้งหมดจากตาราง dep_cates

        // ตัวอย่างข้อมูล pre_name (ถ้าจำเป็น)
        $preNameOptions = ['Mr.', 'Ms.', 'Mrs.'];

        return view('employees.import_leave', compact('sheetNames', 'importedData', 'importMonthYear', 'preNameOptions', 'depCates'));
    }



    // ที่ใช้งานได้ที่ส่งเป็น session
    public function uploadFile(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048',
        ]);

        try {
            $file     = $request->file('file');
            $filePath = $file->getRealPath();

            // อ่านชื่อ Sheet
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($filePath);
            $sheetNames  = $spreadsheet->getSheetNames();

            // เก็บไฟล์ใน session
            session(['uploaded_file' => base64_encode(file_get_contents($filePath)), 'sheet_names' => $sheetNames]);

            $importMonthYear = $request->input('import_month_year');
            $depCateId       = $request->input('dep_cate_id');
            session(['import_month_year' => $importMonthYear, 'depCateId' => $depCateId]);

            // // Debug เช็คค่าทั้งหมด
            // dd([
            //     'import_month_year' => $importMonthYear,
            //     'session_data'      => session()->all(), // ดูค่าทั้งหมดใน session
            // ]);

            return response()->json([
                'status'      => 'success',
                'sheet_names' => $sheetNames,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage(),
            ], 500);

        }
    }

    //ฟังชั่นที่ใช้งานได้
    public function importFromSheet(Request $request)
    {

        $request->validate([
            'sheet_name' => 'required',
            // 'import_month_year' => 'required|date_format:Y-m', //เพิ่มเข้ามาใหม่ 24/3/68 ทดสอบ
        ]);

        try {
            $fileContent = session('uploaded_file');
            if (! $fileContent) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'ไม่พบไฟล์ที่อัปโหลด',
                ], 400);
            }

            $tempFile = tempnam(sys_get_temp_dir(), 'excel');
            file_put_contents($tempFile, base64_decode($fileContent));

            // อ่านข้อมูลจาก Sheet ที่เลือก
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($tempFile);
            $sheet       = $spreadsheet->getSheetByName($request->sheet_name);

            if (! $sheet) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'ไม่พบ Sheet ที่เลือก',
                ], 400);
            }

            $data = $sheet->toArray();
            array_shift($data); // ข้ามบรรทัดแรก (header)

            // แปลงข้อมูลเป็นรูปแบบที่ต้องการ
            $formattedData = [];
            foreach ($data as $row) {
                if (! empty($row[0])) { // ตรวจสอบว่ามีรหัสพนักงาน
                    $formattedData[] = [
                        'employee_code'  => $row[0],
                        'pre_name'       => $row[1],
                        'full_name'      => $row[2],
                        'category'       => $row[3],
                        'position'       => $row[4],
                        'department'     => $row[5],
                        // 'Dep_Category'   => $row[6],
                        'division'       => $row[6],
                        'start_date'     => isset($row[7]) && ! empty($row[7])
                        ? \Carbon\Carbon::parse($row[7])->format('Y-m-d')
                        : null,
                        'personal_leave' => $row[8] ?? 0,
                        'vacation_leave' => $row[9] ?? 0,
                        'sick_leave'     => $row[10] ?? 0,
                        'absent_days'    => $row[11] ?? 0,
                        'late_days'      => $row[12] ?? 0,
                        // 'leave_total'    => ($row[9] ?? 0) + ($row[10] ?? 0) + ($row[11] ?? 0) + ($row[12] ?? 0) + ($row[13] ?? 0),
                        'leave_total'    => $row[13] ?? 0,
                    ];
                }
            }

                                                                                                                                //ฟิกวันให้เป็นวันที่ 1 ของเดือนที่เลือก
            $importMonthYear = $request->import_month_year . '-01';                                                             //ทดสอบ
            $depCateId       = $request->dep_cate_id;                                                                           //เพิ่มเข้ามาใหม่ 28/3/68 ทดสอบ
                                                                                                                                // เก็บข้อมูลไว้ใน session
            session(['imported_data' => $formattedData, 'import_month_year' => $importMonthYear, 'dep_cate_id' => $depCateId]); //ทดสอบ

            // เก็บข้อมูลใน session ตัวเก่าที่ใช้ส่งแต่
            // session(['imported_data' => $formattedData]);

            return response()->json([
                'status'  => 'success',
                'message' => 'นำเข้าข้อมูลสำเร็จ',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage(),
            ], 500);
        }

    }



    //importExcel เก่า ที่ไม่สามารถเลือกชีทเพื่อ import ได้
    public function importExcel(Request $request)
    {
        // ตรวจสอบไฟล์ที่อัปโหลด
        $request->validate([
            'file'              => 'required|mimes:xlsx,csv',
            'import_month_year' => 'required|date_format:Y-m',
        ]);

        // อ่านไฟล์ Excel โดยไม่เก็บไฟล์
        $data = Excel::toArray([], $request->file('file'));

        // ตรวจสอบว่า Excel มีข้อมูลหรือไม่
        if (empty($data) || empty($data[0])) {
            return redirect()->back()->with('error', 'ไฟล์ Excel ไม่มีข้อมูล');
        }

        // แปลงข้อมูลจาก Excel เป็นข้อมูลที่ต้องการ
        $formattedData = [];
        foreach ($data[0] as $row) {
            // ตรวจสอบและแปลงวันที่ให้ถูกต้อง (ถ้าไม่สามารถแปลงได้ให้ใช้วันที่ปัจจุบัน)
            $startDate = $row[8];

            // ตรวจสอบว่า start_date มีค่าหรือไม่ และสามารถแปลงได้
            if (! empty($startDate)) {
                try {
                    // หากวันที่เป็นวันที่แบบ 'd/m/Y'
                    $startDate = \Carbon\Carbon::createFromFormat('d/m/Y', $startDate)->format('Y-m-d');
                } catch (\Exception $e) {
                    // หากไม่สามารถแปลงได้ให้ใช้วันที่ปัจจุบัน
                    $startDate = \Carbon\Carbon::now()->format('Y-m-d');
                }
            } else {
                // หากไม่พบวันที่ ให้ใช้วันที่ปัจจุบัน
                $startDate = \Carbon\Carbon::now()->format('Y-m-d');
            }

            // สร้างข้อมูลที่แปลงแล้ว
            $formattedData[] = [
                'employee_code'  => $row[0],
                'pre_name'       => $row[1],
                'full_name'      => $row[2],
                'category'       => $row[3],
                'position'       => $row[4],
                'department'     => $row[5],
                'Dep_Category'   => $row[6],
                'division'       => $row[7],
                'start_date'     => $startDate,
                'personal_leave' => intval($row[9] ?? 0),
                'vacation_leave' => intval($row[10] ?? 0),
                'sick_leave'     => intval($row[11] ?? 0),
                'absent_days'    => intval($row[12] ?? 0),
                'late_days'      => intval($row[13] ?? 0),
                'leave_total'    => intval($row[9] ?? 0) + intval($row[10] ?? 0) + intval($row[11] ?? 0) + intval($row[12] ?? 0) + intval($row[13] ?? 0),
            ];
        }

        // ฟิกวันให้เป็นวันที่ 1 ของเดือนที่เลือก
        $importMonthYear = $request->import_month_year . '-01';

        // เก็บข้อมูลไว้ใน session
        session(['imported_data' => $formattedData, 'import_month_year' => $importMonthYear]);
        // dd('Step 6: Data imported to session', $formattedData);

        return redirect()->route('import.leave')->with('success', 'นำเข้าข้อมูลสำเร็จ');
    }



    public function submitData(Request $request)
    {
        // dd($request->all());
        // $import_month_year = $request->input('import_month_year', '-01');
        $import_month_year = $request->input('import_month_year');
        $depCateId         = $request->input('dep_cate_id');
        //    dd($import_month_year); // ใช้ dd() เพื่อตรวจสอบค่า
        if (! $import_month_year) {
            return redirect()->back()->withErrors(['import_month_year' => 'กรุณากรอกเดือนและปี']);
        }

        // ตรวจสอบค่าที่ได้รับ
        // dd($depCateId, $import_month_year); // สามารถใช้ dd() เพื่อตรวจสอบค่าที่ได้รับ

        // สามารถใช้ Carbon เพื่อจัดการกับวันที่
        $formattedDate = \Carbon\Carbon::parse($import_month_year)->format('Y-m-d');

        $importedData = session('imported_data');

        // ถ้าไม่มีข้อมูล ให้ redirect กลับพร้อมข้อความแจ้งเตือน
        if (empty($importedData)) {
            return redirect()->back()->with('error', 'ไม่มีข้อมูลนำเข้า กรุณาอัปโหลดไฟล์ใหม่');
        }

        // $depCate = $request->input('dep_cate_id');
        // dd($depCate); //debug drop down แผนก
        // $request->validate([
        //     'dep_cate_id' => 'required|exists:departments,id',
        // ]);

        foreach ($importedData as $data) {
            // $employee = Employee::firstOrCreate(
            //     ['employee_code' => $data['employee_code']],
            //     [
            //         'pre_name'     => $data['pre_name'] ?? null,
            //         'full_name'    => $data['full_name'] ?? null,
            //         'category'     => $data['category'] ?? null,
            //         'position'     => $data['position'] ?? null,
            //         'department'   => $data['department'] ?? null,
            //         'Dep_Category' => $data['Dep_Category'] ?? null,
            //         'division'     => $data['division'] ?? null,
            //         'start_date'   => $data['start_date'] ?? null,
            //     ]
            // );
            $employee = Employee::updateOrCreate(
                ['employee_code' => $data['employee_code']], // Search criteria
                [
                    'pre_name'     => $data['pre_name'] ?? null,
                    'full_name'    => $data['full_name'] ?? null,
                    'category'     => $data['category'] ?? 'G', //ถ้าไม่มีให้เป็น 'G' = อื่นๆ ไม่ให้เป็น null ได้
                    'position'     => $data['position'] ?? null,
                    'department'   => $data['department'] ?? null,
                    // 'Dep_Category' => $data['Dep_Category'] ?? null,
                    'Dep_Category' => $depCateId ?? null,
                    'division'     => $data['division'] ?? null,
                    'start_date'   => $data['start_date'] ?? null,
                ]
            );

            // ค้นหา History ที่มีอยู่แล้วสำหรับ employee และ import_month_year นี้
            $existingHistory = History::where('employee_code', $employee->employee_code)
                ->where('import_month_year', $formattedDate)
                ->first();

            if ($existingHistory) {
                // ถ้ามี History อยู่แล้ว ให้อัปเดตข้อมูล
                $existingHistory->update([
                    'personal_leave' => $data['personal_leave'] ?? 0,
                    'vacation_leave' => $data['vacation_leave'] ?? 0,
                    'sick_leave'     => $data['sick_leave'] ?? 0,
                    'absent_days'    => $data['absent_days'] ?? 0,
                    'late_days'      => $data['late_days'] ?? 0,
                    'leave_total'    => $data['leave_total'] ?? 0,
                ]);
            } else {
                // ถ้าไม่มี History ให้สร้างใหม่
                $history = new History([
                    'employee_code'     => $employee->employee_code,
                    'personal_leave'    => $data['personal_leave'] ?? 0,
                    'vacation_leave'    => $data['vacation_leave'] ?? 0,
                    'sick_leave'        => $data['sick_leave'] ?? 0,
                    'absent_days'       => $data['absent_days'] ?? 0,
                    'late_days'         => $data['late_days'] ?? 0,
                    'leave_total'       => $data['leave_total'] ?? 0,
                    'import_month_year' => $formattedDate,
                ]);

                $history->save();
            }
        }

        session()->forget('imported_data');
        session()->forget('import_month_year');
        session()->forget('dep_cate_id');

        return redirect()->route('import.leave')->with('success', 'ข้อมูลถูกบันทึกเรียบร้อยแล้ว');
    }



    //ฟังชั่นเครียร์ session ข้อมูลในตาราง
    public function clearData()
    {
        // ล้างข้อมูลใน session
        session()->forget('imported_data');

        return redirect()->route('import.leave')->with('success', 'ล้างข้อมูลเรียบร้อยแล้ว!');
    }
}
