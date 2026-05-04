<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // 'pending' = verified but awaiting super admin approval
            // 'approved' = super admin approved, can access system
            // 'rejected' = super admin rejected
            $table->enum('is_approved', ['pending', 'approved', 'rejected'])
                ->default('pending')
                ->after('is_active');
        });

        // Auto-approve existing users so they don't get locked out
        DB::table('users')
            ->whereNotNull('email_verified_at')
            ->update(['is_approved' => 'approved']);
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_approved');
        });
    }
};
