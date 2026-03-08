<?php
require_once __DIR__ . '/../vendor/autoload.php';

// Read .env file
$envFile = __DIR__ . '/../.env';
$env = parse_ini_file($envFile);

$host = $env['DB_HOST'];
$port = $env['DB_PORT'];
$dbname = $env['DB_NAME'];

// Connect to MongoDB
try {
    $client = new MongoDB\Client("mongodb://$host:$port");
    $db = $client->$dbname;
    echo "MongoDB Connected Successfully!";
} catch (Exception $e) {
    die("Connection Failed: " . $e->getMessage());
}
?>