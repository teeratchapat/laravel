<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddImportMonthYearToHistoryTable extends Migration
{
    public function up()
    {
        Schema::table('history', function (Blueprint $table) {
            $table->date('import_month_year')->nullable()->after('leave_total');
        });
    }

    public function down()
    {
        Schema::table('history', function (Blueprint $table) {
            $table->dropColumn('import_month_year');
        });
    }
}
