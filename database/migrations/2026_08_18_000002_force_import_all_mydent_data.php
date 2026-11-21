<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Intentionally a no-op.
     *
     * Previously force-imported production SQL via HTTP-unsafe DB::unprepared.
     * Kept for migration history compatibility — do not restore SQL import logic.
     */
    public function up(): void
    {
        //
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
