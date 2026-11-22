<?php
/**
 * TEMPORARY Hostinger diagnostic — upload to public_html/diag.php
 * Open: https://mydent.in/diag.php
 * DELETE this file immediately after reading results.
 *
 * Does NOT print DB passwords, APP_KEY, or full .env.
 */
header('Content-Type: text/plain; charset=utf-8');
header('X-Robots-Tag: noindex, nofollow');

echo "MyDent Hostinger diag\n";
echo "Time: " . gmdate('c') . "\n";
echo "PHP: " . PHP_VERSION . "\n";
echo "SAPI: " . PHP_SAPI . "\n\n";

$public = __DIR__;
$appBase = realpath($public . '/../mydent_app_new') ?: ($public . '/../mydent_app_new');

echo "== Paths ==\n";
echo "public_html: {$public}\n";
echo "appBase (resolved): {$appBase}\n";
echo "appBase exists: " . (is_dir($appBase) ? 'YES' : 'NO') . "\n";

$checks = [
    'vendor/autoload.php',
    'bootstrap/app.php',
    'artisan',
    'composer.json',
    '.env',
    'storage/logs',
    'storage/framework',
    'storage/framework/cache',
    'storage/framework/sessions',
    'storage/framework/views',
    'bootstrap/cache',
];

echo "\n== App files ==\n";
foreach ($checks as $rel) {
    $path = rtrim($appBase, '/') . '/' . $rel;
    $ok = file_exists($path) || is_dir($path);
    $writable = $ok && (is_dir($path) ? is_writable($path) : is_writable(dirname($path)));
    echo sprintf("%-40s %s%s\n", $rel, $ok ? 'OK' : 'MISSING', ($ok && is_dir($path)) ? (' writable=' . (is_writable($path) ? 'YES' : 'NO')) : '');
}

echo "\n== Public entry ==\n";
foreach (['index.php', '.htaccess', 'mix-manifest.json', 'css/front-pages.css', 'js/front-pages.js'] as $rel) {
    echo sprintf("%-30s %s\n", $rel, file_exists($public . '/' . $rel) ? 'OK' : 'MISSING');
}

echo "\n== Sensitive leak check (must be NO) ==\n";
foreach (['.env', 'artisan', 'composer.json'] as $rel) {
    echo "public_html/{$rel}: " . (file_exists($public . '/' . $rel) ? 'EXPOSED (bad)' : 'absent (good)') . "\n";
}

echo "\n== .env keys (names/status only) ==\n";
$envPath = rtrim($appBase, '/') . '/.env';
if (! is_file($envPath)) {
    echo "NO .env at mydent_app_new/.env  <-- likely 500 cause\n";
} else {
    $raw = file_get_contents($envPath);
    $map = [];
    foreach (preg_split("/\r\n|\n|\r/", $raw) as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#') || ! str_contains($line, '=')) {
            continue;
        }
        [$k, $v] = explode('=', $line, 2);
        $map[$k] = trim($v, " \t\"'");
    }
    $show = ['APP_ENV', 'APP_DEBUG', 'APP_URL', 'DB_CONNECTION', 'DB_HOST', 'DB_PORT', 'DB_DATABASE', 'DB_USERNAME', 'CACHE_DRIVER', 'SESSION_DRIVER', 'FILESYSTEM_DISK'];
    foreach ($show as $k) {
        echo $k . '=' . ($map[$k] ?? '(missing)') . "\n";
    }
    echo 'APP_KEY=' . (empty($map['APP_KEY'] ?? '') ? 'EMPTY' : 'SET') . "\n";
    echo 'DB_PASSWORD=' . (empty($map['DB_PASSWORD'] ?? '') ? 'EMPTY' : 'SET') . "\n";

    if (($map['DB_CONNECTION'] ?? '') === 'sqlite') {
        echo "WARN: DB_CONNECTION=sqlite is wrong for Hostinger MySQL\n";
    }
    if (($map['APP_ENV'] ?? '') !== 'production') {
        echo "WARN: APP_ENV should be production\n";
    }
    if (($map['APP_DEBUG'] ?? '') === 'true') {
        echo "WARN: APP_DEBUG=true on production\n";
    }
    if (str_contains($map['APP_URL'] ?? '', '127.0.0.1') || str_contains($map['APP_URL'] ?? '', 'localhost')) {
        echo "WARN: APP_URL looks local\n";
    }
}

echo "\n== Bootstrap cache ==\n";
$configCache = rtrim($appBase, '/') . '/bootstrap/cache/config.php';
if (is_file($configCache)) {
    $c = file_get_contents($configCache) ?: '';
    echo "config.php present\n";
    if (str_contains($c, '/Volumes/storage/')) {
        echo "WARN: config cache contains Mac path /Volumes/storage/\n";
    }
    if (str_contains($c, 'database.sqlite')) {
        echo "WARN: config cache references database.sqlite\n";
    }
} else {
    echo "config.php absent (OK for first boot)\n";
}

echo "\n== Laravel boot test ==\n";
$autoload = rtrim($appBase, '/') . '/vendor/autoload.php';
$bootstrap = rtrim($appBase, '/') . '/bootstrap/app.php';
if (! is_file($autoload) || ! is_file($bootstrap)) {
    echo "FAIL: cannot boot — missing vendor/autoload.php or bootstrap/app.php\n";
    exit;
}

try {
    require $autoload;
    $app = require $bootstrap;
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    echo "Laravel bootstrap: OK\n";
    echo 'app.env=' . config('app.env') . "\n";
    echo 'app.debug=' . (config('app.debug') ? 'true' : 'false') . "\n";
    echo 'app.url=' . config('app.url') . "\n";
    echo 'db.default=' . config('database.default') . "\n";
    try {
        Illuminate\Support\Facades\DB::connection()->getPdo();
        echo "DB connection: OK\n";
    } catch (Throwable $e) {
        echo 'DB connection: FAIL — ' . $e->getMessage() . "\n";
    }
} catch (Throwable $e) {
    echo "Laravel bootstrap: FAIL\n";
    echo 'Type: ' . get_class($e) . "\n";
    echo 'Message: ' . $e->getMessage() . "\n";
    echo 'File: ' . $e->getFile() . ':' . $e->getLine() . "\n";
}

echo "\n== Latest log tail (no secrets filter) ==\n";
$logDir = rtrim($appBase, '/') . '/storage/logs';
$latest = null;
if (is_dir($logDir)) {
    $files = glob($logDir . '/laravel*.log') ?: [];
    rsort($files);
    $latest = $files[0] ?? null;
}
if (! $latest) {
    echo "No laravel*.log yet\n";
} else {
    echo 'File: ' . basename($latest) . "\n";
    $lines = @file($latest) ?: [];
    $tail = array_slice($lines, -40);
    foreach ($tail as $line) {
        $line = preg_replace('/(password|secret|key|token)=([^\s]+)/i', '$1=***', $line);
        echo $line;
    }
}

echo "\nDONE — delete public_html/diag.php now.\n";
