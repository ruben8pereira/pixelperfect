<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            // Add new columns if they don't exist
            if (!Schema::hasColumn('reports', 'report_number')) {
                $table->string('report_number')->nullable()->after('title');
            }

            if (!Schema::hasColumn('reports', 'inspection_date')) {
                $table->date('inspection_date')->nullable()->after('language');
            }

            if (!Schema::hasColumn('reports', 'operator')) {
                $table->string('operator')->nullable()->after('inspection_date');
            }

            if (!Schema::hasColumn('reports', 'client')) {
                $table->string('client')->nullable()->after('operator');
            }

            if (!Schema::hasColumn('reports', 'location')) {
                $table->string('location')->nullable()->after('client');
            }

            if (!Schema::hasColumn('reports', 'intervention_reason')) {
                $table->string('intervention_reason')->nullable()->after('location');
            }

            if (!Schema::hasColumn('reports', 'weather')) {
                $table->string('weather')->nullable()->after('intervention_reason');
            }

            if (!Schema::hasColumn('reports', 'share_token')) {
                $table->string('share_token')->nullable()->after('weather');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            // Remove added columns
            $table->dropColumn([
                'report_number',
                'inspection_date',
                'operator',
                'client',
                'location',
                'intervention_reason',
                'weather',
                'share_token'
            ]);
        });
    }
};
