<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->string('report_number')->after('title');
            $table->date('inspection_date')->nullable()->after('language');
            $table->string('client')->nullable()->after('inspection_date');
            $table->string('operator')->nullable()->after('client');
            $table->string('intervention_reason')->nullable()->after('operator');
            $table->string('weather')->nullable()->after('intervention_reason');
            $table->string('location')->nullable()->after('weather');
        });
    }

    public function down()
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->dropColumn([
                'report_number',
                'inspection_date',
                'client',
                'operator',
                'intervention_reason',
                'weather',
                'location'
            ]);
        });
    }
};

