<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * Proves PHPUnit cannot touch development SQLite or production MySQL.
 */
class PhpunitDatabaseIsolationTest extends TestCase
{
    public function test_phpunit_uses_in_memory_sqlite_only(): void
    {
        $this->assertSame('testing', config('app.env'));
        $this->assertSame('sqlite', config('database.default'));
        $this->assertSame('sqlite', DB::connection()->getDriverName());
        $this->assertSame(':memory:', config('database.connections.sqlite.database'));

        $devSqlite = database_path('database.sqlite');
        $this->assertNotSame($devSqlite, config('database.connections.sqlite.database'));
        $this->assertNotSame('mysql', config('database.default'));
    }

    public function test_refresh_style_schema_does_not_change_dev_sqlite_file(): void
    {
        $devSqlite = database_path('database.sqlite');
        $this->assertFileExists($devSqlite);
        $beforeSize = filesize($devSqlite);
        $beforeMtime = filemtime($devSqlite);
        $beforeUsers = $this->countUsersInDevSqlite($devSqlite);

        // Simulate destructive test work against the PHPUnit connection only.
        Schema::dropAllTables();
        Schema::create('isolation_probe', function ($table) {
            $table->id();
            $table->string('marker');
        });
        DB::table('isolation_probe')->insert(['marker' => 'phpunit-only']);

        clearstatcache(true, $devSqlite);
        $afterSize = filesize($devSqlite);
        $afterMtime = filemtime($devSqlite);
        $afterUsers = $this->countUsersInDevSqlite($devSqlite);

        $this->assertSame($beforeSize, $afterSize, 'Dev SQLite file size changed during PHPUnit');
        $this->assertSame($beforeMtime, $afterMtime, 'Dev SQLite mtime changed during PHPUnit');
        $this->assertSame($beforeUsers, $afterUsers, 'Dev SQLite user count changed during PHPUnit');
        $this->assertSame(1, (int) DB::table('isolation_probe')->count());
    }

    private function countUsersInDevSqlite(string $path): int
    {
        $pdo = new \PDO('sqlite:'.$path);
        $result = $pdo->query("SELECT COUNT(*) FROM sqlite_master WHERE type='table' AND name='users'");
        if ((int) $result->fetchColumn() === 0) {
            return -1;
        }

        return (int) $pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
    }
}
