<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('history', function (Blueprint $table) {
            $table->integer('id_his')->primary(); // ใช้เป็น PK
            $table->string('employee_code');
            $table->integer('personal_leave')->default(0);
            $table->integer('vacation_leave')->default(0);
            $table->integer('sick_leave')->default(0);
            $table->integer('absent_days')->default(0);
            $table->integer('late_days')->default(0);
            $table->integer('leave_total')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('history');
    }
};
