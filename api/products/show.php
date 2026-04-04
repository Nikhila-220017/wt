<?php
require_once __DIR__ . '/../../lib/helpers.php';
// GET /api/products/{id}
$products = getCollection('products');
$product  = $products->findOne(['_id' => $_GET['id']]);

if (!$product) error('Product not found.', 404);
success(['product' => $product]);
