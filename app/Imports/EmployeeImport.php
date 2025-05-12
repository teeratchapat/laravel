<?php

// namespace App\Imports;

// use Maatwebsite\Excel\Concerns\ToCollection;
// use Illuminate\Support\Collection;
// use App\Models\Employee;
// use App\Models\History;
// use Carbon\Carbon;
// class EmployeeImport implements ToCollection
// {
//     public function collection(Collection $rows)
//     {
//         foreach ($rows as $index => $row) {
//             if ($index === 0) continue; // ข้ามหัวตาราง

//             // เช็คว่าพนักงานนี้มีอยู่แล้วหรือไม่
//             $employee = Employee::updateOrCreate(
//                 ['employee_code' => $row[0]], // คีย์หลักในการเช็ค
//                 [
//                     'pre_name'   => $row[1],
//                     'full_name' => $row[2],
//                     'category'   => $row[3],
//                     'position'   => $row[4],
//                     'department' => $row[5],
//                     'division'   => $row[6],
//                     'start_date' => Carbon::parse($row[7])->format('Y-m-d'),
//                 ]
//             );

//             // เพิ่มข้อมูลในตาราง history (ไม่ต้องอัปเดต เพราะเป็นประวัติย้อนหลัง)
//             History::create([
//                 'employee_code'   => $employee->employee_code, // ใช้ employee_code เป็นตัวเชื่อม
//                 'personal_leave'  => $row[8] ?? 0,
//                 'vacation_leave'  => $row[9] ?? 0,
//                 'sick_leave'      => $row[10] ?? 0,
//                 'absent_days'     => $row[11] ?? 0,
//                 'late_days'       => $row[12] ?? 0,
//             ]);
//         }
//     }
// }

// namespace App\Imports;

// use App\Models\Employee;
// use Maatwebsite\Excel\Concerns\ToModel;

// class EmployeeImport implements ToModel
// {
//     public function model(array $row)
//     {
//         return new Employee([
//             'employee_code' => $row[0],
//             'pre_name'      => $row[1],
//             'full_name'     => $row[2],
//             'category'      => $row[3],
//             'position'      => $row[4],
//             'department'    => $row[5],
//             'Dep_Category'  => $row[6],
//             'division'      => $row[7],
//             'start_date'    => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[8])->format('Y-m-d'),
//         ]);
//     }
// }

//backup-20-3-68
namespace App\Imports;

use App\Models\Employee;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class EmployeeImport implements ToModel, WithHeadingRow
{
    protected $sheetName;

    public function __construct($sheetName)
    {
        $this->sheetName = $sheetName;
    }

    public function model(array $row)
    {
        return new Employee([
            'employee_code' => $row['employee_code'],
            'pre_name'      => $row['pre_name'],
            'full_name'     => $row['full_name'],
            'category'      => $row['category'],
            'position'      => $row['position'],
            'department'    => $row['department'],
            'Dep_Category'  => $row['Dep_Category'],
            'division'      => $row['division'],
            'start_date'    => isset($row['start_date']) && ! empty($row['start_date'])
            ? \Carbon\Carbon::parse($row['start_date'])->format('Y-m-d')
            : null,
        ]);
    }
}
