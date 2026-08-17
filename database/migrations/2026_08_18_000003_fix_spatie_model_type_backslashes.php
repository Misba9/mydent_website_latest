<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations to normalize model_type string escaping for Spatie roles/permissions.
     */
    public function up(): void
    {
        if (Schema::hasTable('model_has_roles')) {
            try {
                DB::statement("UPDATE model_has_roles SET model_type = 'App\\\\Models\\\\User'");
            } catch (\Throwable $e) {
            }
        }

        if (Schema::hasTable('model_has_permissions')) {
            try {
                DB::statement("UPDATE model_has_permissions SET model_type = 'App\\\\Models\\\\User'");
            } catch (\Throwable $e) {
            }
        }

        // Import complete updated SQL dump
        $sqlPath = base_path('mydent_database_production_import.sql');
        if (file_exists($sqlPath)) {
            $sql = file_get_contents($sqlPath);
            $statements = array_filter(array_map('trim', explode(";\n", $sql)));
            foreach ($statements as $stmt) {
                if (!empty($stmt) && !str_starts_with($stmt, '--')) {
                    try {
                        DB::unprepared($stmt . ';');
                    } catch (\Throwable $e) {
                    }
                }
            }
        }

        if (Schema::hasTable('model_has_roles')) {
            try {
                DB::statement("UPDATE model_has_roles SET model_type = 'App\\\\Models\\\\User'");
            } catch (\Throwable $e) {
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};
