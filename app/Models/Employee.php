<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $table = 'employees'; // ชื่อตาราง
    protected $primaryKey = 'employee_code'; // คีย์หลักของตาราง
    protected $keyType = 'string'; // บอกให้ Laravel ใช้ string แทน int
    public $incrementing = false; // ป้องกัน auto-increment

    protected $fillable = [
        'employee_code',
        'pre_name',
        'full_name',
        'position',
        'department',
        'Dep_Category',
        'division',
        'category',
        'start_date',
    ];

    public function histories()
    {
        return $this->hasMany(History::class, 'employee_code', 'employee_code');
    }
}
