<?php
require_once __DIR__ . '/../../lib/helpers.php';
// GET /api/orders/{id}

$auth   = require_auth();
$userId = $auth['userId'];

$ordCol = getCollection('orders');
$order  = $ordCol->findOne(['_id' => $_GET['id']]);

if (!$order) {
    error('Order not found.', 404);
}

// Make sure this order belongs to the logged-in user
if ($order['userId'] !== $userId) {
    error('Access denied.', 403);
}

success(['order' => $order]);
