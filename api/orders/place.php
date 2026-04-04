<?php
require_once __DIR__ . '/../../lib/helpers.php';
// POST /api/orders/place

$auth   = require_auth();
$userId = $auth['userId'];
$body   = get_body();

$cartCol = getCollection('cart');
$prodCol = getCollection('products');
$ordCol  = getCollection('orders');

$cartItems = $cartCol->find(['userId' => $userId]);

if (empty($cartItems)) {
    error('Your cart is empty. Add items before placing an order.');
}

$orderItems  = [];
$totalAmount = 0;

foreach ($cartItems as $item) {
    $product = $prodCol->findOne(['_id' => $item['productId']]);

    if (!$product) continue;

    if ($product['stock'] < $item['quantity']) {
        error("Sorry, only {$product['stock']} units of '{$product['name']}' are available.");
    }

    $subtotal      = $product['price'] * $item['quantity'];
    $totalAmount  += $subtotal;

    $orderItems[] = [
        'productId'   => $item['productId'],
        'productName' => $product['name'],
        'category'    => $product['category'],
        'image'       => $product['image'],
        'price'       => $product['price'],
        'quantity'    => $item['quantity'],
        'subtotal'    => $subtotal,
    ];

    // Reduce stock
    $prodCol->updateOne(
        ['_id' => $item['productId']],
        ['$set' => ['stock' => $product['stock'] - $item['quantity']]]
    );
}

// Create the order document in MongoDB
$order = $ordCol->insertOne([
    'userId'          => $userId,
    'customerName'    => $auth['name'],
    'customerEmail'   => $auth['email'],
    'items'           => $orderItems,
    'totalAmount'     => $totalAmount,
    'itemCount'       => count($orderItems),
    'deliveryAddress' => $body['address'] ?? 'Not provided',
    'status'          => 'placed',
    'paymentMethod'   => $body['paymentMethod'] ?? 'Cash on Delivery',
]);

// Clear the cart after successful order
$cartCol->deleteMany(['userId' => $userId]);

success([
    'message' => "Order placed successfully! Thank you for shopping at Kumari's Store.",
    'order'   => $order,
], 201);
