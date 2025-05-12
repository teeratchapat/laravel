<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeptCat extends Model
{
    use HasFactory;

    protected $table = 'dep_cate'; // ชื่อตาราง
    protected $primaryKey = 'dep_cate_id'; // คีย์หลักของตาราง
    // protected $keyType = 'string'; // บอกให้ Laravel ใช้ string แทน int
    public $incrementing = false; // ป้องกัน auto-increment

    protected $fillable = [
        'dep_cate_id',
        'dep_cate_name',
    ];


}
