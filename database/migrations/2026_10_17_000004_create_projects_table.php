<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Projects — the buildings and hotels managed by HANSA.
     *
     * Before this table the eleven projects were written out inside
     * pages/projects.blade.php, and four of them were written out a second time
     * inside pages/home/sections/_projects.blade.php with longer marketing
     * titles. Both presentations are kept: the homepage card text lives in the
     * `homepage_*` columns and falls back to the main text when left empty.
     */
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('location');
            $table->string('category')->default('ongoing');
            $table->string('image')->nullable();
            $table->string('image_alt')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->string('homepage_title')->nullable();
            $table->string('homepage_location')->nullable();
            $table->string('homepage_alt')->nullable();
            $table->unsignedInteger('homepage_sort')->default(0);
            $table->unsignedInteger('sort')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
