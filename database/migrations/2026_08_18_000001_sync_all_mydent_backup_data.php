<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Intentionally a no-op.
     *
     * Previously imported mydent_database_production_import.sql via DB::unprepared
     * on migrate, which is unsafe for staging/production. Kept for migration history
     * compatibility — do not restore SQL import logic here.
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
