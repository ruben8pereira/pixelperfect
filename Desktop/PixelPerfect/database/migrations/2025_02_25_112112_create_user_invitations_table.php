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
    Schema::create('user_invitations', function (Blueprint $table) {
        $table->id();
        $table->string('email');
        $table->foreignId('organization_id')->constrained();
        $table->foreignId('invited_by')->constrained('users');
        $table->foreignId('role_id')->constrained();
        $table->string('token', 64)->unique();
        $table->timestamp('expires_at');
        $table->boolean('is_used')->default(false);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_invitations');
    }
};
