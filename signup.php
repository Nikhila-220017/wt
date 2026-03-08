<?php
require_once __DIR__ . '/../vendor/autoload.php';

$envFile = __DIR__ . '/../.env';
$env = parse_ini_file($envFile);

$host = $env['DB_HOST'];
$port = $env['DB_PORT'];
$dbname = $env['DB_NAME'];

try {
    $client = new MongoDB\Client("mongodb://$host:$port");
    $db = $client->$dbname;
    $users = $db->users;

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Validate empty fields
    if(empty($name) || empty($email) || empty($password)){
        die("All fields are required!");
    }

    // Validate password length
    if(strlen($password) < 6){
        die("Password must be at least 6 characters!");
    }

    // Check duplicate email
    $existing = $users->findOne(['email' => $email]);
    if($existing){
        die("Email already exists! Please use a different email.");
    }

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert new user
    $users->insertOne([
        'name' => $name,
        'email' => $email,
        'password' => $hashedPassword,
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ]);

    header("Location: ../public/login.html?signup=success");
    exit();

} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
?>