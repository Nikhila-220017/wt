<?php
require_once __DIR__ . '/../../lib/helpers.php';
// POST /api/auth/login
// Body: { "email": "...", "password": "..." }

$body = get_body();
require_fields($body, ['email', 'password']);

$email    = strtolower(trim($body['email']));
$password = $body['password'];

$users = getCollection('users');
$user  = $users->findOne(['email' => $email]);

// Use same error for wrong email OR wrong password (security best practice)
if (!$user || !password_verify($password, $user['password'])) {
    error('Invalid email or password.', 401);
}

// Generate JWT token (7-day expiry)
$token = JWT::encode([
    'userId' => $user['_id'],
    'name'   => $user['name'],
    'email'  => $user['email'],
    'role'   => $user['role'] ?? 'customer',
]);

success([
    'message' => "Welcome back, {$user['name']}! 👋",
    'token'   => $token,
    'user'    => [
        '_id'   => $user['_id'],
        'name'  => $user['name'],
        'email' => $user['email'],
        'role'  => $user['role'] ?? 'customer',
    ],
]);
