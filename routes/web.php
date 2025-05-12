<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\ImportCsvController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ReportLeaveHisController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\DashboardController;

// ใช้สำหรับ Excel

// หน้า login
Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.process');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// แสดงฟอร์มสมัครสมาชิก
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
// ทำการบันทึกข้อมูลสมัครสมาชิก
Route::post('/register', [RegisterController::class, 'register'])->name('register.process');

// ✅ เส้นทางสำหรับ Import ข้อมูล CSV (ไม่ใช้ auth เพื่อตรวจสอบก่อน)
Route::prefix('import/csv')->name('import.csv.')->group(function () {
    Route::get('/', [ImportCsvController::class, 'showForm'])->name('form');
    Route::post('/upload', [ImportCsvController::class, 'uploadCsv'])->name('upload'); // 🔍 ทดสอบนอก auth
});

// ✅ เส้นทางที่ต้องล็อกอินก่อนเข้า
Route::middleware(['auth'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/history_leave', [LeaveController::class, 'index'])->name('history.leave');
    Route::get('/report/leave-history', [ReportLeaveHisController::class, 'index'])->name('report.leave-history');
    Route::get('/export-leave-report', [ReportLeaveHisController::class, 'exportReport'])->name('report.export'); //เพิ่มroute 4/4/68 ในการเชื่อมไปฟั่งชั่น exportReport Excel

    Route::resource('employees', EmployeeController::class);

    //dashboard
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // ✅ เส้นทางสำหรับ Import ข้อมูล CSV ที่ต้องล็อกอิน
    Route::prefix('import/csv')->name('import.csv.')->group(function () {
        Route::post('/submit', [ImportCsvController::class, 'submitData'])->name('submit');
    });

    // ✅ เส้นทางสำหรับ Import ข้อมูล Leave
    Route::get('/import/leave', [ImportController::class, 'showForm'])->name('import.leave');

    // ✅ เส้นทางสำหรับ Import ข้อมูล Excel
    Route::prefix('import/excel')->name('import.excel.')->group(function () {
        Route::get('/', [ImportController::class, 'showForm'])->name('form');
        Route::post('/upload', [ImportController::class, 'uploadFile'])->name('upload');
        Route::post('/sheet', [ImportController::class, 'importFromSheet'])->name('sheet');
        Route::post('/submit', [ImportController::class, 'submitData'])->name('submit');
        Route::post('/clear', [ImportController::class, 'clearData'])->name('clear');
    });
});
