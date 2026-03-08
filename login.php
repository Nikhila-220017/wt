<?php
session_start();
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

    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Validate empty fields
    if(empty($email) || empty($password)){
        die("All fields are required!");
    }

    // Find user by email
    $user = $users->findOne(['email' => $email]);

    if(!$user){
        die("Invalid email or password!");
    }

    // Verify password
    if(!password_verify($password, $user['password'])){
        die("Invalid email or password!");
    }

    // Set session
    $_SESSION['user_id'] = (string)$user['_id'];
    $_SESSION['user_name'] = $user['name'];
    $_SESSION['user_email'] = $user['email'];

    header("Location: ../public/dashboard.php");
    exit();

} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
?>