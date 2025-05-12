<?php


namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'user_login'; // ✅ เปลี่ยนเป็นชื่อตารางที่มีอยู่จริง

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
}
