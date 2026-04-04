<?php
require_once __DIR__ . '/../../lib/helpers.php';
// POST /api/products  (admin only)
// Body: { name, category, price, stock, image, description }

$auth = require_auth();
if (($auth['role'] ?? '') !== 'admin') {
    error('Admin access required.', 403);
}

$body = get_body();
require_fields($body, ['name', 'category', 'price', 'stock']);

if (!is_numeric($body['price']) || $body['price'] < 0) {
    error('Price must be a positive number.');
}
if (!is_numeric($body['stock']) || $body['stock'] < 0) {
    error('Stock must be a positive number.');
}

$products = getCollection('products');
$product  = $products->insertOne([
    'name'        => trim($body['name']),
    'category'    => strtolower(trim($body['category'])),
    'price'       => (float)$body['price'],
    'stock'       => (int)$body['stock'],
    'image'       => $body['image']       ?? '',
    'description' => $body['description'] ?? '',
]);

success(['message' => 'Product added successfully!', 'product' => $product], 201);
