<?php

// namespace App\Imports;

// use App\Models\History;
// use Maatwebsite\Excel\Concerns\ToModel;

// class HistoryImport implements ToModel
// {
//     public function model(array $row)
//     {
//         return new History([
//             'employee_code'  => $row[0],
//             'personal_leave' => $row[9],
//             'vacation_leave' => $row[10],
//             'sick_leave'     => $row[11],
//             'absent_days'    => $row[12],
//             'late_days'      => $row[13],
//             'leave_total'    => $row[14],
//         ]);
//     }
// }


namespace App\Imports;

use App\Models\History;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class HistoryImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new History([
            'employee_code'  => $row['employee_code'],
            'personal_leave' => $row['personal_leave'] ?? 0,
            'vacation_leave' => $row['vacation_leave'] ?? 0,
            'sick_leave'     => $row['sick_leave'] ?? 0,
            'absent_days'    => $row['absent_days'] ?? 0,
            'late_days'      => $row['late_days'] ?? 0,
            'leave_total'    => $row['leave_total'] ?? 0,

            // คำนวณ leave_total จาก 5 ฟิลด์ด้านบน
            // 'leave_total' => ($row['personal_leave'] ?? 0) + ($row['vacation_leave'] ?? 0) + ($row['sick_leave'] ?? 0) + ($row['absent_days'] ?? 0) + ($row['late_days'] ?? 0),
        ]);
    }
}
