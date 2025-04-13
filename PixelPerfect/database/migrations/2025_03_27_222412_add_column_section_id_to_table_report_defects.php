<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('report_defects', function (Blueprint $table) {
            $table->foreignId('section_id')->nullable()->after('defect_type_id')
                  ->references('id')->on('report_sections')
                  ->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('report_defects', function (Blueprint $table) {
            $table->dropForeign(['section_id']);
            $table->dropColumn('section_id');
        });
    }
};
