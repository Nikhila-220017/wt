<?php
// ================================================================
//  lib/helpers.php  —  Shared utilities used by every endpoint
// ================================================================

require_once __DIR__ . '/JWT.php';
require_once __DIR__ . '/MongoDB.php';

// ── JSON response helpers ────────────────────────────────────

function json_response(mixed $data, int $status = 200): never {
    http_response_code($status);
    header('Content-Type: application/json');
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit;
}

function success(mixed $data, int $status = 200): never {
    json_response($data, $status);
}

function error(string $message, int $status = 400): never {
    json_response(['error' => $message], $status);
}

// ── CORS headers (allow your HTML page to call this API) ─────
function set_cors(): void {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization');
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        http_response_code(200);
        exit;
    }
}

// ── Parse JSON request body ──────────────────────────────────
function get_body(): array {
    $raw = file_get_contents('php://input');
    return json_decode($raw, true) ?? [];
}

// ── Require a valid JWT and return the payload ───────────────
function require_auth(): array {
    $authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
    if (!preg_match('/Bearer\s+(.+)/i', $authHeader, $m)) {
        error('Access denied. Please log in first.', 401);
    }
    $payload = JWT::decode($m[1]);
    if (!$payload) {
        error('Invalid or expired token. Please log in again.', 403);
    }
    return $payload;   // contains: userId, name, email
}

// ── Simple input validation ──────────────────────────────────
function require_fields(array $body, array $fields): void {
    foreach ($fields as $f) {
        if (empty($body[$f])) {
            error("Field '$f' is required.");
        }
    }
}
