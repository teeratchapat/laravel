<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class UserLogin extends Authenticatable
{
    use Notifiable;

    protected $table = 'user_login'; // ชื่อตารางในฐานข้อมูล

    protected $primaryKey = 'id'; // คีย์หลัก

    protected $fillable = [
        'user_name',
        'password',
        'pre_name',
        'first_name',
        'last_name',
        'user_status',
        'position',
        'department',
    ];

    protected $hidden = [
        'password',
    ];

    public function getAuthPassword()
    {
        return $this->password;
    }
}
