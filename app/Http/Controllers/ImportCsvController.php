<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\History;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\CsvImport;
use Illuminate\Support\Facades\Storage;

class ImportCsvController extends Controller
{
    // แสดงฟอร์มสำหรับอัปโหลด CSV
    public function showForm()
    {
        return view('ImportFile.import_csv'); // ให้ไปที่ import_csv.blade.php
    }

    // ดำเนินการอัปโหลดไฟล์ CSV
    // public function uploadCsv(Request $request)
    // {
    //     $request->validate([
    //         'csv_file' => 'required|mimes:csv,txt|max:2048'
    //     ]);

    //     $file = $request->file('csv_file');

    //     // 🔴 เช็คว่า Laravel เขียนไฟล์ได้ไหม
    //     $path = storage_path('app/csv_uploads/test.txt');
    //     file_put_contents($path, 'ทดสอบเขียนไฟล์');

    //     // ถ้าเขียนสำเร็จให้แสดงข้อความนี้
    //     dd('เขียนไฟล์สำเร็จ: ' . $path);
    // }


    // public function uploadCsv(Request $request)
    // {
    //     dd('uploadCsv method called'); // เช็คว่าเข้าฟังก์ชันนี้จริงไหม

    //     $request->validate([
    //         'csv_file' => 'required|mimes:csv,txt|max:2048'
    //     ]);

    //     $file = $request->file('csv_file');
    //     $path = $file->store('csv_uploads');

    //     $fullPath = storage_path('app/' . $path);
    //     if (!file_exists($fullPath)) {
    //         return back()->with('error', 'ไม่พบไฟล์ CSV');
    //     }

    //     $csvData = [];
    //     if (($handle = fopen($fullPath, 'r')) !== false) {
    //         while (($data = fgetcsv($handle, 1000, ",")) !== false) {
    //             $csvData[] = $data;
    //         }
    //         fclose($handle);
    //     }

    //     dd($csvData);
    // }

    // public function uploadCsv(Request $request)
    // {
    //     // ✅ ตรวจสอบและ validate ไฟล์
    //     $request->validate([
    //         'csv_file' => 'required|mimes:csv,txt|max:2048'
    //     ]);

    //     $file = $request->file('csv_file');

    //     // ✅ สร้างโฟลเดอร์หากไม่มี
    //     $csvFolderPath = storage_path('app/csv_uploads');
    //     if (!is_dir($csvFolderPath)) {
    //         mkdir($csvFolderPath, 0777, true);
    //     }

    //     // ✅ เปลี่ยนชื่อไฟล์ให้ไม่ซ้ำ
    //     $filename = time() . '_' . $file->getClientOriginalName();
    //     $path = 'csv_uploads/' . $filename;

    //     // ✅ บันทึกไฟล์ลง storage
    //     $file->storeAs('csv_uploads', $filename);

    //     // ✅ ตรวจสอบว่าไฟล์ถูกบันทึกจริงไหม
    //     $fullPath = storage_path('app/' . $path);
    //     if (!file_exists($fullPath)) {
    //         return back()->with('error', 'ไฟล์ถูกอัปโหลดแต่ไม่พบในโฟลเดอร์');
    //     }

    //     // ✅ อ่านข้อมูล CSV และแสดงออกมา
    //     $csvData = array_map('str_getcsv', file($fullPath));

    //     // ✅ Debug: ตรวจสอบข้อมูลที่อ่านได้
    //     dd([
    //         'Uploaded Path' => $path,
    //         'Full Path' => $fullPath,
    //         'File Exists' => file_exists($fullPath),
    //         'CSV Data' => $csvData
    //     ]);
    // }

    public function uploadCsv(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|mimes:csv,txt|max:2048'
        ]);

        $file = $request->file('csv_file');
        $filename = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('csv_uploads', $filename); // ✅ บันทึกไฟล์ใน storage/app/csv_uploads

        if (!Storage::exists($path)) {
            dd('ไฟล์ไม่พบที่ ' . storage_path('app/' . $path));
        }

        $fullPath = storage_path('app/' . $path);
        $csvData = array_map('str_getcsv', file($fullPath));

        dd($csvData); // ✅ Debug เช็คข้อมูล CSV
    }




    // บันทึกข้อมูลจาก CSV ลงฐานข้อมูล
    public function submitData(Request $request)
    {
        $data = $request->input('csv_data');

        foreach ($data as $row) {
            Employee::updateOrCreate(
                ['employee_code' => $row['0']], // ค้นหาด้วย employee_code
                [
                    'pre_name'     => $row['1'],
                    'full_name'    => $row['2'],
                    'position'     => $row['3'],
                    'department'   => $row['4'],
                    'Dep_Category' => $row['5'],
                    'division'     => $row['6'],
                    'category'     => $row['7'],
                    'start_date'   => date('Y-m-d', strtotime($row['8'])), // แปลงวันที่
                ]
            );

            History::create([
                'employee_code'  => $row['0'],
                'personal_leave' => $row['9'],
                'vacation_leave' => $row['10'],
                'sick_leave'     => $row['11'],
                'absent_days'    => $row['12'],
                'late_days'      => $row['13'],
                'leave_total'    => $row['14'],
            ]);
        }

        return redirect()->route('import.csv.form')->with('success', 'นำเข้าข้อมูลสำเร็จ');
    }
}
