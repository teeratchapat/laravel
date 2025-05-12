<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('user_login', function (Blueprint $table) {
            $table->id();
            $table->string('user_name', 50)->unique();
            $table->string('password');
            $table->string('pre_name', 50);
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->string('user_status', 50);
            $table->string('position', 100)->nullable();
            $table->string('department', 100)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_login');
    }
};
