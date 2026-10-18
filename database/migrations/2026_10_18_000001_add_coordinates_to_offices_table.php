<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Optional map coordinates for an office.
     *
     * Deliberately nullable and left empty. An office only earns a marker once a
     * member of staff has entered a WGS 84 latitude/longitude pair that has been
     * checked against the real location. The legacy site carried placeholder
     * coordinates — three of its four markers shared one identical pair — so no
     * value is back-filled here. The map stays off until someone fills these in
     * from admin → Settings → Offices.
     *
     * Decimal(10, 7) is wide enough for every valid coordinate (at most three
     * digits before the point) and keeps about one centimetre of precision.
     */
    public function up(): void
    {
        Schema::table('offices', function (Blueprint $table) {
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('offices', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude']);
        });
    }
};
