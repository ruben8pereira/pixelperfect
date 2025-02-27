<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // Update existing roles to match the specified requirements
        DB::table('roles')->updateOrInsert(
            ['name' => 'Administrator'],
            [
                'name' => 'Administrator',
                'description' => 'Business owner with system-wide management capabilities'
            ]
        );

        DB::table('roles')->updateOrInsert(
            ['name' => 'Organization'],
            [
                'name' => 'Organization',
                'description' => 'Organizational manager with access to organization-specific reports and invitations'
            ]
        );

        DB::table('roles')->updateOrInsert(
            ['name' => 'RegisteredUser'],
            [
                'name' => 'RegisteredUser',
                'description' => 'Paying user with ability to create, edit, and share reports'
            ]
        );

        DB::table('roles')->updateOrInsert(
            ['name' => 'Guest'],
            [
                'name' => 'Guest',
                'description' => 'User with limited access to shared reports'
            ]
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Optional: If you want to revert changes
        DB::table('roles')->whereIn('name', [
            'Administrator',
            'Organization',
            'RegisteredUser',
            'Guest'
        ])->delete();
    }
};

