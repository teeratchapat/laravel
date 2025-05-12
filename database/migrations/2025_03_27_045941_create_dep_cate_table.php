<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDepCateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dep_cate', function (Blueprint $table) {
            $table->string('dep_cate_id', 8)->primary(); // dep_cat_id เป็น primary key
            $table->string('dep_cate_name', 30);         // dep_cat_name เป็น varchar(30)
            $table->timestamps();                       // สำหรับเพิ่ม created_at และ updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dep_cate');
    }
}
