<?php
require_once __DIR__ . '/../../lib/helpers.php';
// GET /api/products/categories
$products   = getCollection('products');
$all        = $products->find([]);
$categories = array_values(array_unique(array_column($all, 'category')));
sort($categories);
success(['categories' => $categories]);
