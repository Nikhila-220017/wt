<?php
require_once __DIR__ . '/../../lib/helpers.php';
// GET /api/orders/my-orders

$auth   = require_auth();
$userId = $auth['userId'];

$ordCol = getCollection('orders');
$orders = $ordCol->find(['userId' => $userId]);

// Sort newest first
usort($orders, fn($a, $b) => strcmp($b['createdAt'] ?? '', $a['createdAt'] ?? ''));

success([
    'count'  => count($orders),
    'orders' => $orders,
]);
