<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('venues', function (Blueprint $table) {
            $table->string('color', 7)->default('#6c757d')->after('building');
        });

        // Assign colors to existing venues
        $colors = [
            // NDRRMOC Building — blue shades
            'Meeting Room A'      => '#1a3c72',
            'Meeting Room B'      => '#2a5298',
            'NDRRMOC Room'        => '#3b6fd4',
            'NDRRMOC VIP Room'    => '#4a90d9',
            // NAB Building — orange/red shades
            'Multimedia Room'         => '#e87722',
            'NAB VIP Room'            => '#dc3545',
            'Main Conference Room'    => '#c0392b',
            '8th Floor Multipurpose Room' => '#e74c3c',
        ];

        foreach ($colors as $name => $color) {
            DB::table('venues')->where('name', $name)->update(['color' => $color]);
        }
    }

    public function down(): void
    {
        Schema::table('venues', function (Blueprint $table) {
            $table->dropColumn('color');
        });
    }
};
