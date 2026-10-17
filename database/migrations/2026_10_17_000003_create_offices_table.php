<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Office locations.
     *
     * Before this table the four offices were written out twice — once in
     * pages/contact.blade.php and once in pages/home/sections/_offices.blade.php
     * — and every card displayed the same mobile number and the same email
     * address. The contact page now reads these records, so a per-office phone
     * number is either real or absent, never duplicated by accident.
     */
    public function up(): void
    {
        Schema::create('offices', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('address');
            $table->string('phone')->nullable();
            $table->string('landline')->nullable();
            $table->string('email')->nullable();
            $table->unsignedInteger('sort')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('offices');
    }
};
