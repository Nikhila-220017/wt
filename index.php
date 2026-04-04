<?php
// ================================================================
//  index.php  —  Main router for Kumari's Store API
//
//  Start server: php -S localhost:8000
//  All requests go through this file (like .htaccess rewrite)
// ================================================================

require_once __DIR__ . '/lib/helpers.php';

set_cors();

$method = $_SERVER['REQUEST_METHOD'];
$uri    = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri    = rtrim($uri, '/');

// ── Route table ──────────────────────────────────────────────
// Auth
if ($uri === '/api/auth/register' && $method === 'POST') {
    require __DIR__ . '/api/auth/register.php';

} elseif ($uri === '/api/auth/login' && $method === 'POST') {
    require __DIR__ . '/api/auth/login.php';

// Products
} elseif ($uri === '/api/products/categories' && $method === 'GET') {
    require __DIR__ . '/api/products/categories.php';

} elseif (preg_match('#^/api/products/([a-f0-9]{24})$#', $uri, $m) && $method === 'GET') {
    $_GET['id'] = $m[1];
    require __DIR__ . '/api/products/show.php';

} elseif ($uri === '/api/products' && $method === 'GET') {
    require __DIR__ . '/api/products/index.php';

} elseif ($uri === '/api/products' && $method === 'POST') {
    require __DIR__ . '/api/products/store.php';

} elseif (preg_match('#^/api/products/([a-f0-9]{24})$#', $uri, $m) && $method === 'PUT') {
    $_GET['id'] = $m[1];
    require __DIR__ . '/api/products/update.php';

} elseif (preg_match('#^/api/products/([a-f0-9]{24})$#', $uri, $m) && $method === 'DELETE') {
    $_GET['id'] = $m[1];
    require __DIR__ . '/api/products/delete.php';

// Cart
} elseif ($uri === '/api/cart' && $method === 'GET') {
    require __DIR__ . '/api/cart/index.php';

} elseif ($uri === '/api/cart/add' && $method === 'POST') {
    require __DIR__ . '/api/cart/add.php';

} elseif (preg_match('#^/api/cart/([a-f0-9]{24})$#', $uri, $m) && $method === 'DELETE') {
    $_GET['productId'] = $m[1];
    require __DIR__ . '/api/cart/remove.php';

} elseif ($uri === '/api/cart' && $method === 'DELETE') {
    require __DIR__ . '/api/cart/clear.php';

// Orders
} elseif ($uri === '/api/orders/place' && $method === 'POST') {
    require __DIR__ . '/api/orders/place.php';

} elseif ($uri === '/api/orders/my-orders' && $method === 'GET') {
    require __DIR__ . '/api/orders/my_orders.php';

} elseif (preg_match('#^/api/orders/([a-f0-9]{24})$#', $uri, $m) && $method === 'GET') {
    $_GET['id'] = $m[1];
    require __DIR__ . '/api/orders/show.php';

// Root health check
} elseif ($uri === '' || $uri === '/') {
    success([
        'message' => "🛍️ Welcome to Kumari's Store API!",
        'status'  => 'running',
        'endpoints' => [
            'POST /api/auth/register',
            'POST /api/auth/login',
            'GET  /api/products',
            'GET  /api/products?category=kurtha',
            'GET  /api/products?q=blue&sort=price_asc',
            'GET  /api/products/{id}',
            'POST /api/products  (admin)',
            'PUT  /api/products/{id}  (admin)',
            'DELETE /api/products/{id}  (admin)',
            'GET  /api/cart',
            'POST /api/cart/add',
            'DELETE /api/cart/{productId}',
            'DELETE /api/cart',
            'POST /api/orders/place',
            'GET  /api/orders/my-orders',
            'GET  /api/orders/{id}',
        ]
    ]);

} else {
    error("Route not found: $method $uri", 404);
}
