<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run non-destructive sync migration to restore original MyDent database records.
     */
    public function up(): void
    {
        $sqlPath = base_path('mydent_database_production_import.sql');
        if (file_exists($sqlPath)) {
            $sql = file_get_contents($sqlPath);
            $statements = array_filter(array_map('trim', explode(";\n", $sql)));
            foreach ($statements as $stmt) {
                if (!empty($stmt) && !str_starts_with($stmt, '--')) {
                    try {
                        DB::unprepared($stmt . ';');
                    } catch (\Throwable $e) {
                        // Continue gracefully on duplicate key or existing index
                    }
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Non-destructive: preserve database records
    }
};
