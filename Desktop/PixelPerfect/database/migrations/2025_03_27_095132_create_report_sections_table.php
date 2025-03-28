<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('report_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')->constrained('reports')->onDelete('cascade');
            $table->string('name');
            $table->integer('diameter')->nullable();
            $table->string('material')->nullable();
            $table->decimal('length', 8, 2)->nullable();
            $table->string('start_manhole')->nullable();
            $table->string('end_manhole')->nullable();
            $table->string('location')->nullable();
            $table->text('comments')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('report_sections');
    }
};
