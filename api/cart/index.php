<?php
require_once __DIR__ . '/../../lib/helpers.php';
// GET /api/cart  (requires login)
$auth     = require_auth();
$userId   = $auth['userId'];

$cartCol  = getCollection('cart');
$prodCol  = getCollection('products');

$items    = $cartCol->find(['userId' => $userId]);

// Enrich each cart item with full product details
$enriched = [];
$total    = 0;
foreach ($items as $item) {
    $product = $prodCol->findOne(['_id' => $item['productId']]);
    if ($product) {
        $subtotal   = $product['price'] * $item['quantity'];
        $total     += $subtotal;
        $enriched[] = [
            '_id'       => $item['_id'],
            'quantity'  => $item['quantity'],
            'subtotal'  => $subtotal,
            'product'   => $product,
        ];
    }
}

success([
    'cartItems'   => $enriched,
    'itemCount'   => count($enriched),
    'totalAmount' => $total,
]);
