<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    use HasFactory;

    protected $table = 'history'; // ชื่อตาราง
    protected $primaryKey = 'id_his'; // กำหนด primary key
    public $incrementing = true; // ใช้ auto-increment
    protected $keyType = 'int'; // กำหนดชนิดข้อมูลของ primary key

    protected $fillable = [
        'employee_code',
        'personal_leave',
        'vacation_leave',
        'sick_leave',
        'absent_days',
        'late_days',
        'leave_total',
        'import_month_year'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_code', 'employee_code');
    }
}

// //edit 21/3/68
// namespace App\Models;

// use Carbon\Carbon;
// use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;

// class History extends Model
// {
//     use HasFactory;
//     protected $table      = 'history'; // กำหนดชื่อตารางให้ถูกต้อง
//     protected $primaryKey = 'id_his';
//     public $incrementing  = true;
//     protected $keyType    = 'int';

//     protected $fillable = [
//         'employee_code',
//         'personal_leave',
//         'vacation_leave',
//         'sick_leave',
//         'absent_days',
//         'late_days',
//         'leave_total',
//         'import_month_year',
//         'created_at',
//         'updated_at',
//     ];

//     // เพิ่ม casting สำหรับ dates
//     protected $casts = [
//         'created_at'        => 'datetime',
//         'updated_at'        => 'datetime',
//         'import_month_year' => 'date',
//     ];

//     // ความสัมพันธ์กับ Employee model
//     public function employee()
//     {
//         return $this->belongsTo(Employee::class, 'employee_code', 'employee_code');
//     }

//     // Accessor สำหรับแปลงวันที่เป็นรูปแบบไทย
//     public function getImportMonthYearThaiAttribute()
//     {
//         if ($this->import_month_year) {
//             $date = Carbon::parse($this->import_month_year);
//             return $date->format('m/Y');
//         }
//         return null;
//     }

//     // Scope query สำหรับดึงข้อมูลล่าสุด
//     public function scopeLatestImports($query, $limit = 100)
//     {
//         return $query->with('employee')
//             ->orderBy('created_at', 'desc')
//             ->limit($limit);
//     }

//     // ฟังก์ชันคำนวณ leave_total
//     public function calculateLeaveTotal()
//     {
//         return $this->personal_leave +
//         $this->vacation_leave +
//         $this->sick_leave +
//         $this->absent_days +
//         $this->late_days;
//     }

//     // Boot method สำหรับ auto-calculate leave_total
//     protected static function boot()
//     {
//         parent::boot();

//         static::saving(function ($history) {
//             $history->leave_total = $history->calculateLeaveTotal();
//         });
//     }
// }
