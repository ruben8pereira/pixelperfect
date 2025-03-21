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
        Schema::table('report_images', function (Blueprint $table) {
            // Add the defect_id column if it doesn't exist
            if (!Schema::hasColumn('report_images', 'defect_id')) {
                $table->foreignId('defect_id')->nullable()->after('report_id')
                      ->references('id')->on('report_defects')
                      ->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('report_images', function (Blueprint $table) {
            if (Schema::hasColumn('report_images', 'defect_id')) {
                $table->dropForeign(['defect_id']);
                $table->dropColumn('defect_id');
            }
        });
    }
};
