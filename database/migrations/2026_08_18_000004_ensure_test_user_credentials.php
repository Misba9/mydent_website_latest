<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Intentionally a no-op.
     *
     * Previously reset admin/doctor/patient passwords to a weak known value (123456).
     * Kept for migration history compatibility. Use seeders only in local/dev, and
     * rotate any production accounts that may have been affected.
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
