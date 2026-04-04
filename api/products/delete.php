<?php
require_once __DIR__ . '/../../lib/helpers.php';
// DELETE /api/products/{id}  (admin only)
$auth = require_auth();
if (($auth['role'] ?? '') !== 'admin') error('Admin access required.', 403);

$products = getCollection('products');
$deleted  = $products->deleteOne(['_id' => $_GET['id']]);

if (!$deleted) error('Product not found.', 404);
success(['message' => 'Product deleted successfully.']);
