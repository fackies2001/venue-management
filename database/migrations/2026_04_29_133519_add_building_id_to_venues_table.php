<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // Essential for the data copying step

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('venues', function (Blueprint $table) {
            // Only drop the old string column if it still exists
            if (Schema::hasColumn('venues', 'building')) {
                $table->dropColumn('building');
            }
        });
    }

    public function down(): void
    {
        Schema::table('venues', function (Blueprint $table) {
            $table->string('building')->nullable();
        });
    }
};
