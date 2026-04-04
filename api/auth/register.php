<?php
require_once __DIR__ . '/../../lib/helpers.php';
// POST /api/auth/register
// Body: { "name": "...", "email": "...", "password": "..." }

$body = get_body();
require_fields($body, ['name', 'email', 'password']);

$name     = trim($body['name']);
$email    = strtolower(trim($body['email']));
$password = $body['password'];

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    error('Please enter a valid email address.');
}

// Validate password length
if (strlen($password) < 6) {
    error('Password must be at least 6 characters long.');
}

// Validate name
if (strlen($name) < 2) {
    error('Name must be at least 2 characters.');
}

$users = getCollection('users');

// Check if email already registered
if ($users->findOne(['email' => $email])) {
    error('An account with this email already exists.', 409);
}

// Hash the password — NEVER store plain text passwords
$hashed = password_hash($password, PASSWORD_BCRYPT, ['cost' => 10]);

// Insert into MongoDB users collection
$user = $users->insertOne([
    'name'     => $name,
    'email'    => $email,
    'password' => $hashed,
    'role'     => 'customer',   // 'customer' or 'admin'
]);

// Generate JWT token
$token = JWT::encode([
    'userId' => $user['_id'],
    'name'   => $user['name'],
    'email'  => $user['email'],
    'role'   => $user['role'],
]);

success([
    'message' => "Welcome to Kumari's Store, {$user['name']}! 🎉",
    'token'   => $token,
    'user'    => [
        '_id'   => $user['_id'],
        'name'  => $user['name'],
        'email' => $user['email'],
        'role'  => $user['role'],
    ],
], 201);
