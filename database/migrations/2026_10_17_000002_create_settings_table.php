<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Editable site settings, stored as key/value pairs.
     *
     * Only public values live here (phone numbers, social links, analytics id,
     * …). Secrets — mail credentials, APP_KEY, database credentials — stay in
     * .env and are deliberately not editable from the admin panel: a database
     * row is plaintext, readable by every admin, and stolen with any leak.
     *
     * Defaults for every key live in config/site.php, so the site works with an
     * empty table.
     */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
