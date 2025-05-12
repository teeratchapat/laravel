<?php
namespace App\Jobs;

use App\Models\Employee;
use App\Models\History;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessExcelImport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $formattedData;

    public function __construct(array $formattedData)
    {
        $this->formattedData = $formattedData;
    }

    public function handle()
    {
        try {
            Log::info('Starting to process ' . count($this->formattedData) . ' records');

            foreach ($this->formattedData as $data) {
                // บันทึกข้อมูลพนักงาน
                $employee = Employee::updateOrCreate(
                    ['employee_code' => $data['employee_code']],
                    [
                        'pre_name'     => $data['pre_name'],
                        'full_name'    => $data['full_name'],
                        'category'     => $data['category'],
                        'position'     => $data['position'],
                        'department'   => $data['department'],
                        'Dep_Category' => $data['Dep_Category'],
                        'division'     => $data['division'],
                        'start_date'   => $data['start_date'],
                    ]
                );

                // บันทึกประวัติการลา
                History::create([
                    'employee_code'  => $data['employee_code'],
                    'personal_leave' => $data['personal_leave'],
                    'vacation_leave' => $data['vacation_leave'],
                    'sick_leave'     => $data['sick_leave'],
                    'absent_days'    => $data['absent_days'],
                    'late_days'      => $data['late_days'],
                    'leave_total'    => $data['leave_total'],
                ]);

                Log::info('Processed employee: ' . $data['employee_code']);
            }

            Log::info('Completed processing chunk');
        } catch (\Exception $e) {
            Log::error('Error processing chunk: ' . $e->getMessage());
            throw $e;
        }
    }
}
