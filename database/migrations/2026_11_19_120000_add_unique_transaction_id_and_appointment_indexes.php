<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Additive indexes only — does not delete existing business data.
     */
    public function up(): void
    {
        $duplicateTransactionIds = DB::table('transactions')
            ->select('transaction_id')
            ->groupBy('transaction_id')
            ->havingRaw('COUNT(*) > 1')
            ->exists();

        if (! $duplicateTransactionIds && Schema::hasTable('transactions')) {
            Schema::table('transactions', function (Blueprint $table) {
                $table->unique('transaction_id');
            });
        }

        if (Schema::hasTable('appointments')) {
            Schema::table('appointments', function (Blueprint $table) {
                $table->index(['doctor_id', 'date', 'status'], 'appointments_doctor_date_status_index');
                $table->index('appointment_unique_id', 'appointments_unique_id_index');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('transactions')) {
            Schema::table('transactions', function (Blueprint $table) {
                $table->dropUnique(['transaction_id']);
            });
        }

        if (Schema::hasTable('appointments')) {
            Schema::table('appointments', function (Blueprint $table) {
                $table->dropIndex('appointments_doctor_date_status_index');
                $table->dropIndex('appointments_unique_id_index');
            });
        }
    }
};
