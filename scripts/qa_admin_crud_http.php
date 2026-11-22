<?php

/**
 * Phase 2 local-only Admin CRUD persistence checks (HTTP + SQLite).
 * Does not touch production MySQL.
 */

$base = getenv('BASE_URL') ?: 'http://127.0.0.1:8000';
$cookie = tempnam(sys_get_temp_dir(), 'mydent_admin_');
$results = [];

function req(string $method, string $url, string $cookie, array $opts = []): array
{
    $ch = curl_init($url);
    $headers = $opts['headers'] ?? [];
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => $method,
        CURLOPT_COOKIEJAR => $cookie,
        CURLOPT_COOKIEFILE => $cookie,
        CURLOPT_FOLLOWLOCATION => false,
        CURLOPT_HEADER => true,
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_POSTFIELDS => $opts['body'] ?? null,
    ]);
    $raw = curl_exec($ch);
    $code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $headerSize = (int) curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    curl_close($ch);
    $header = substr((string) $raw, 0, $headerSize);
    $body = substr((string) $raw, $headerSize);

    return compact('code', 'header', 'body');
}

function csrfFrom(string $html): string
{
    if (preg_match('/name="_token"\s+value="([^"]+)"/', $html, $m)) {
        return $m[1];
    }
    if (preg_match('/name="csrf-token"\s+content="([^"]+)"/', $html, $m)) {
        return $m[1];
    }

    return '';
}

function locationFrom(string $header): string
{
    if (preg_match('/^Location:\s*(.+)$/mi', $header, $m)) {
        return trim($m[1]);
    }

    return '';
}

// Login
$loginPage = req('GET', "$base/login", $cookie);
$token = csrfFrom($loginPage['body']);
$login = req('POST', "$base/login", $cookie, [
    'headers' => ['Content-Type: application/x-www-form-urlencoded'],
    'body' => http_build_query([
        'email' => 'admin@mydent.in',
        'password' => '123456',
        '_token' => $token,
    ]),
]);
$results['login'] = [
    'code' => $login['code'],
    'location' => locationFrom($login['header']),
    'ok' => $login['code'] === 302 && str_contains(locationFrom($login['header']), 'admin/dashboard'),
];

// Create specialization via JSON/AJAX-style form post
$stamp = (string) time();
$specPage = req('GET', "$base/admin/specializations", $cookie);
$specToken = csrfFrom($specPage['body']);
$specName = "HTTP Spec $stamp";
$spec = req('POST', "$base/admin/specializations", $cookie, [
    'headers' => [
        'Content-Type: application/x-www-form-urlencoded',
        'X-Requested-With: XMLHttpRequest',
        'Accept: application/json',
    ],
    'body' => http_build_query(['_token' => $specToken, 'name' => $specName]),
]);
$pdo = new PDO('sqlite:'.dirname(__DIR__).'/database/database.sqlite');
$specExists = (int) $pdo->query("SELECT COUNT(*) FROM specializations WHERE name=".$pdo->quote($specName))->fetchColumn();
$results['specialization_create'] = [
    'http' => $spec['code'],
    'db' => $specExists,
    'ok' => $specExists === 1 && $spec['code'] < 500,
];

// Create FAQ
$faqPage = req('GET', "$base/admin/faqs/create", $cookie);
$faqToken = csrfFrom($faqPage['body']);
$q = "HTTP FAQ $stamp?";
$faq = req('POST', "$base/admin/faqs", $cookie, [
    'headers' => ['Content-Type: application/x-www-form-urlencoded'],
    'body' => http_build_query([
        '_token' => $faqToken,
        'question' => $q,
        'answer' => "Answer $stamp",
    ]),
]);
$faqExists = (int) $pdo->query('SELECT COUNT(*) FROM faqs WHERE question='.$pdo->quote($q))->fetchColumn();
$results['faq_create'] = [
    'http' => $faq['code'],
    'location' => locationFrom($faq['header']),
    'db' => $faqExists,
    'ok' => $faqExists === 1 && in_array($faq['code'], [200, 302], true),
];

// Create patient
$patientPage = req('GET', "$base/admin/patients/create", $cookie);
$patientToken = csrfFrom($patientPage['body']);
$unique = '';
if (preg_match('/name="patient_unique_id"[^>]*value="([^"]*)"/', $patientPage['body'], $m)) {
    $unique = $m[1];
}
if ($unique === '') {
    $unique = substr('H'.$stamp, 0, 8);
}
$email = "http.patient.$stamp@example.com";
// Omit contact: shared demo numbers collide on users.contact unique rule.
$patient = req('POST', "$base/admin/patients", $cookie, [
    'headers' => ['Content-Type: application/x-www-form-urlencoded'],
    'body' => http_build_query([
        '_token' => $patientToken,
        'first_name' => 'Http',
        'last_name' => "Patient$stamp",
        'email' => $email,
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'patient_unique_id' => $unique,
        'gender' => 1,
    ]),
]);
$userExists = (int) $pdo->query('SELECT COUNT(*) FROM users WHERE email='.$pdo->quote($email))->fetchColumn();
$results['patient_create'] = [
    'http' => $patient['code'],
    'location' => locationFrom($patient['header']),
    'db' => $userExists,
    'ok' => $userExists === 1 && in_array($patient['code'], [200, 302], true),
];

// Module HTTP status matrix (authenticated)
$paths = [
    '/admin/dashboard', '/admin/doctors', '/admin/patients', '/admin/appointments',
    '/admin/services', '/admin/specializations', '/admin/staffs', '/admin/roles',
    '/admin/products', '/orders', '/admin/transactions', '/admin/faqs',
    '/admin/settings', '/admin/cms', '/admin/enquiries', '/admin/subscribers',
    '/admin/visits', '/admin/medicines', '/admin/main-banners', '/profile/edit',
    '/blogs/create', '/products/create',
];
$matrix = [];
foreach ($paths as $path) {
    $r = req('GET', $base.$path, $cookie);
    $matrix[$path] = $r['code'];
}
$results['module_matrix'] = $matrix;
$results['module_matrix_ok'] = count(array_filter($matrix, fn ($c) => $c >= 500)) === 0;

@unlink($cookie);

$failed = [];
foreach (['login', 'specialization_create', 'faq_create', 'patient_create'] as $k) {
    if (empty($results[$k]['ok'])) {
        $failed[] = $k;
    }
}
if (! $results['module_matrix_ok']) {
    $failed[] = 'module_matrix';
}

echo json_encode($results, JSON_PRETTY_PRINT).PHP_EOL;
exit($failed ? 1 : 0);
