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
            $table->string('id')->primary()->comment('Unique identifier used to access public information about the secret.');
            $table->string('access_token')->comment('Hashed unique identifier used to authorize decryption or destruction of the secret.');
            $table->text('content')->nullable()->comment('Encrypted payload of the secret.');
            $table->string('passphrase')->nullable()->comment('Optional hashed passphrase required to authorize decryption or destruction of the secret.');
            $table->timestamp('expires_at')->index()->comment('Timestamp after which the secret is no longer accessible.');
            $table->timestamp('revealed_at')->nullable()->index()->comment('Timestamp of when the secret was decrypted and payload was destroyed.');
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
