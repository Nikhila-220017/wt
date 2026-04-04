<?php
require_once __DIR__ . '/../../lib/helpers.php';
// DELETE /api/cart/{productId}

$auth      = require_auth();
$userId    = $auth['userId'];
$productId = $_GET['productId'];

$cartCol = getCollection('cart');
$deleted = $cartCol->deleteOne(['userId' => $userId, 'productId' => $productId]);

if (!$deleted) {
    error('Item not found in your cart.', 404);
}

success(['message' => 'Item removed from cart.']);
