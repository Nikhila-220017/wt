<?php
require_once __DIR__ . '/../../lib/helpers.php';
// POST /api/cart/add
// Body: { "productId": "...", "quantity": 1 }

$auth     = require_auth();
$userId   = $auth['userId'];
$body     = get_body();

require_fields($body, ['productId']);

$productId = $body['productId'];
$quantity  = isset($body['quantity']) ? (int)$body['quantity'] : 1;

if ($quantity < 1) {
    error('Quantity must be at least 1.');
}

$prodCol = getCollection('products');
$product = $prodCol->findOne(['_id' => $productId]);

if (!$product) {
    error('Product not found.', 404);
}

if ($product['stock'] < $quantity) {
    error("Only {$product['stock']} items in stock.");
}

$cartCol  = getCollection('cart');
$existing = $cartCol->findOne(['userId' => $userId, 'productId' => $productId]);

if ($existing) {
    $newQty  = $existing['quantity'] + $quantity;
    if ($product['stock'] < $newQty) {
        error("Only {$product['stock']} items in stock. You already have {$existing['quantity']} in cart.");
    }
    $updated = $cartCol->updateOne(
        ['userId' => $userId, 'productId' => $productId],
        ['$set' => ['quantity' => $newQty]]
    );
    success([
        'message'  => "{$product['name']} quantity updated in cart!",
        'cartItem' => array_merge($updated, ['product' => $product])
    ]);
} else {
    $cartItem = $cartCol->insertOne([
        'userId'    => $userId,
        'productId' => $productId,
        'quantity'  => $quantity,
    ]);
    success([
        'message'  => "{$product['name']} added to cart!",
        'cartItem' => array_merge($cartItem, ['product' => $product])
    ], 201);
}
