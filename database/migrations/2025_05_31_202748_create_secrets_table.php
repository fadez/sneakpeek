<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('secrets', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique()->comment('The key used to access the info about the secret');
            $table->string('secret_key')->unique()->comment('The key used to reveal the secret');
            $table->longText('content')->nullable();
            $table->string('passphrase')->nullable();
            $table->timestamp('expires_at')->nullable()->index();
            $table->timestamp('revealed_at')->nullable()->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('secrets');
    }
};
