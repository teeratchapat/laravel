<?php
// namespace App\Exports;

// use Maatwebsite\Excel\Concerns\FromCollection;
// use Maatwebsite\Excel\Concerns\WithHeadings;

// class LeaveReportExport implements FromCollection, WithHeadings
// {
//     protected $reportData_xlsx;

//     public function __construct($reportData_xlsx)
//     {
//         $this->reportData_xlsx = $reportData_xlsx;
//     }

//     public function collection()
//     {
//         return $this->reportData_xlsx;
//     }

//     public function headings(): array
//     {
//         return [
//             'รหัสพนักงาน',
//             'ชื่อ-นามสกุล พนักงาน',
//             'แผนก',
//             'ลากิจ',
//             'พักร้อน',
//             'ลาป่วย',
//             'ขาด',
//             'สาย',
//             'รวม',
//         ];
//     }
// }

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;

class LeaveReportExport implements FromCollection, WithHeadings, WithEvents, ShouldAutoSize
{
    protected $reportData_xlsx;
    protected $categoryLabels = [
        'A' => 'ผู้จัดการ',
        'B' => 'เลขานุการ',
        'C' => 'หัวหน้าหน่วย',
        'D' => 'หัวหน้ากลุ่ม',
        'E' => 'พนักงาน/ช่างทั่วไป',
        'F' => 'เจ้าหน้าที่',
        'G' => 'อื่น ๆ',
    ];

    public function __construct($reportData_xlsx)
    {
        $this->reportData_xlsx = $reportData_xlsx;
    }

    public function collection()
    {
        // จัดกลุ่มข้อมูลตามหมวดหมู่และแผนก
        $sortedData = $this->reportData_xlsx->sortBy([
            'category',
            'employee_code',
            'dep_cate_name',
        ]);

        $result = new Collection();

        // จัดกลุ่มตาม category
        $categoryGrouped = $sortedData->groupBy('category');

        // เพิ่มข้อมูลทุกๆหมวดหมู่
        foreach ($categoryGrouped as $categoryCode => $categoryData) {
            $categoryName = $this->categoryLabels[$categoryCode] ?? $categoryCode;
            $result->push([$categoryName, '', '', '', '', '', '', '', '']); // แถวหัวข้อหมวดหมู่

            // จัดกลุ่มตามแผนกภายในหมวดหมู่
            $departmentGrouped = $categoryData->groupBy('dep_cate_name');

            foreach ($departmentGrouped as $department => $employees) {
                                                                                       // แถวหัวข้อแผนก
                $result->push(["    " . $department, '', '', '', '', '', '', '', '']); // แถวหัวข้อแผนก

                // เพิ่มข้อมูลพนักงาน
                foreach ($employees as $employee) {
                    $result->push([
                        $employee->employee_code,
                        $employee->name,
                        $employee->dep_cate_name,
                        $employee->personal_leave,
                        $employee->vacation_leave,
                        $employee->sick_leave,
                        $employee->absent_days,
                        $employee->late_days,
                        $employee->leave_total,
                    ]);
                }
            }
        }

        return $result;
    }

    public function headings(): array
    {
        return [
            'รหัสพนักงาน',
            'ชื่อพนักงาน',
            'แผนก',
            'ลากิจ',
            'พักร้อน',
            'ลาป่วย',
            'ขาด',
            'สาย',
            'รวม',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet      = $event->sheet;
                $highestRow = $sheet->getHighestRow();

                // ปรับแต่งการจัดรูปแบบหัวข้อ
                $sheet->getStyle('A1:I1')->applyFromArray([
                    'font' => ['bold' => true],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'color'    => ['argb' => 'FFD9D9D9'],
                    ],
                ]);

                // เพิ่มการจัดรูปแบบหัวข้อหมวดหมู่และแผนก
                for ($row = 2; $row <= $highestRow; $row++) {
                    $valueA = $sheet->getCell('A' . $row)->getValue();
                    $valueB = $sheet->getCell('B' . $row)->getValue();

                    if (! empty($valueA) && empty($valueB)) {
                        // จัดการกับหัวข้อหมวดหมู่และแผนก
                        if (substr($valueA, 0, 4) === '    ') {
                            $sheet->getStyle('A' . $row . ':I' . $row)->applyFromArray([
                                'font' => ['bold' => true],
                                'fill' => [
                                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                                    'color'    => ['argb' => 'FFEEEEEE'],
                                ],
                            ]);
                        } else {
                            $sheet->getStyle('A' . $row . ':I' . $row)->applyFromArray([
                                'font' => ['bold' => true],
                                'fill' => [
                                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                                    'color'    => ['argb' => 'FFD0D0D0'],
                                ],
                            ]);
                        }

                        $sheet->mergeCells('A' . $row . ':I' . $row);
                    }
                }
            },
        ];
    }
}
