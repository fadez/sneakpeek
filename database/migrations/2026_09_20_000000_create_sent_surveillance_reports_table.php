<?php

declare(strict_types=1);

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
        Schema::create('sent_surveillance_reports', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('agency_id');
            $table->string('case_number');
            $table->timestamp('sent_at');

            $table->unique(['agency_id', 'case_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sent_surveillance_reports');
    }
};
