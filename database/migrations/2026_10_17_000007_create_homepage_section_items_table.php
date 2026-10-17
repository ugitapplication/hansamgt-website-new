<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The repeating items inside the homepage's own sections.
     *
     * Four sections are driven from here, told apart by `section`:
     *
     *   commitments  our three policy cards     image, icon, title, body
     *   numbers      "HANSA in Numbers"         value, title (the label)
     *   glance       services at-a-glance       image, title, body
     *   why          why to choose HANSA        body, group (1 or 2 = column)
     *
     * The "Our Services" cards and the "Service Projects" cards are NOT here:
     * the first belongs to the Services content type and the second is the
     * featured flag on the Projects table.
     */
    public function up(): void
    {
        Schema::create('homepage_section_items', function (Blueprint $table) {
            $table->id();
            $table->string('section');
            $table->string('title')->nullable();
            $table->text('body')->nullable();
            $table->string('value')->nullable();
            $table->unsignedSmallInteger('value_from_year')->nullable();
            $table->string('image')->nullable();
            $table->string('image_alt')->nullable();
            $table->string('icon')->nullable();
            $table->string('group')->nullable();
            $table->unsignedInteger('sort')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['section', 'sort']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('homepage_section_items');
    }
};
