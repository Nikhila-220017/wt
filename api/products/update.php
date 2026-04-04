<?php
require_once __DIR__ . '/../../lib/helpers.php';
// PUT /api/products/{id}  (admin only)
$auth = require_auth();
if (($auth['role'] ?? '') !== 'admin') error('Admin access required.', 403);

$products = getCollection('products');
if (!$products->findOne(['_id' => $_GET['id']])) error('Product not found.', 404);

$body   = get_body();
$fields = [];
if (isset($body['name']))        $fields['name']        = trim($body['name']);
if (isset($body['category']))    $fields['category']    = strtolower(trim($body['category']));
if (isset($body['price']))       $fields['price']       = (float)$body['price'];
if (isset($body['stock']))       $fields['stock']       = (int)$body['stock'];
if (isset($body['image']))       $fields['image']       = $body['image'];
if (isset($body['description'])) $fields['description'] = $body['description'];

if (empty($fields)) error('No fields to update.');

$updated = $products->updateOne(['_id' => $_GET['id']], ['$set' => $fields]);
success(['message' => 'Product updated successfully!', 'product' => $updated]);
