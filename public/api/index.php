<?php

header('Content-Type: application/json');

$is_dev = file_exists('../../package.json');
ini_set('error_reporting', E_ALL);
ini_set('display_errors', (int) $is_dev);
ini_set('display_startup_errors', (int) $is_dev);

$payload = json_decode($_GET['payload'] ?? $_POST['payload'] ?? file_get_contents('php://input') ?? '{}', true);
if ($is_dev) {
	$_['payload'] = $payload;
}

$env = parse_ini_file('../../.env');
$db = mysqli_connect($env['DB_IP'], $env['DB_USER'], $env['DB_PASS'], $env['DB_NAME']);
mysqli_set_charset($db, 'utf8');

require_once 'utils.php';

if (file_exists($endpoint = 'endpoints' . $payload['page'] . '.php')) {
	require_once $endpoint;
	if ($is_dev && isset($query)) {
		$_['query'] = $query;
	}
} else {
	$_['error'] = 'Endpoint не поддерживается!';
}

exit(json_encode($_ ?? ['ok' => true], JSON_UNESCAPED_UNICODE));
