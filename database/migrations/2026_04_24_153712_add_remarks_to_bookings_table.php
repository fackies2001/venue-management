<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Idadagdag natin ang 'remarks' pagkatapos ng 'attachment_path' para maayos tingnan sa database
            $table->text('remarks')->nullable()->after('attachment_path');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Tatanggalin ang column kung sakaling mag-rollback tayo
            $table->dropColumn('remarks');
        });
    }
};
