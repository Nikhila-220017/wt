<?php
require_once __DIR__ . '/../../lib/helpers.php';
// DELETE /api/cart

$auth   = require_auth();
$userId = $auth['userId'];

$cartCol = getCollection('cart');
$count   = $cartCol->deleteMany(['userId' => $userId]);

success(['message' => "Cart cleared. $count item(s) removed."]);
