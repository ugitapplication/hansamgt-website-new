<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Marks the accounts that may enter the Filament admin panel.
     *
     * Deliberately defaults to false: existing rows (including any development
     * accounts) are not silently promoted. Promote an administrator explicitly
     * with the `admin:create` command or a deliberate database update.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_admin')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_admin');
        });
    }
};
