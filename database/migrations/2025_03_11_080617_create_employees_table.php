<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->string('employee_code')->primary(); // ใช้เป็น PK
            $table->string('pre_name', 50);
            $table->string('full_name', 100);
            $table->string('position', 100);
            $table->string('department', 100);
            $table->string('division', 100);
            $table->string('category', 100);
            $table->string('Dep_Category', 100);
            $table->date('start_date');
            $table->timestamps(); // created_at, updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
