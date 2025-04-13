<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('report_defects', function (Blueprint $table) {
        $table->id();
        $table->foreignId('report_id')->constrained()->onDelete('cascade');
        $table->foreignId('defect_type_id')->constrained();
        $table->text('description')->nullable();
        $table->string('severity')->nullable();
        $table->json('coordinates')->nullable(); // For GPS coordinates
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_defects');
    }
};
