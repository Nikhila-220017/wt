<?php
require_once __DIR__ . '/../../lib/helpers.php';
// GET /api/products
// Query params:
//   ?category=kurtha          → filter by category
//   ?q=blue                   → search name or description
//   ?minPrice=200             → minimum price
//   ?maxPrice=1000            → maximum price
//   ?sort=price_asc | price_desc | name_asc | newest
//   ?page=1&limit=10          → pagination

$products = getCollection('products');
$all      = $products->find([]);

// ── Search by name / description ────────────────────────────
if (!empty($_GET['q'])) {
    $q   = strtolower(trim($_GET['q']));
    $all = array_filter($all, fn($p) =>
        str_contains(strtolower($p['name'] ?? ''), $q) ||
        str_contains(strtolower($p['category'] ?? ''), $q) ||
        str_contains(strtolower($p['description'] ?? ''), $q)
    );
}

// ── Filter by category ───────────────────────────────────────
if (!empty($_GET['category'])) {
    $cat = strtolower(trim($_GET['category']));
    $all = array_filter($all, fn($p) => strtolower($p['category'] ?? '') === $cat);
}

// ── Price range filter ───────────────────────────────────────
if (isset($_GET['minPrice'])) {
    $min = (float)$_GET['minPrice'];
    $all = array_filter($all, fn($p) => ($p['price'] ?? 0) >= $min);
}
if (isset($_GET['maxPrice'])) {
    $max = (float)$_GET['maxPrice'];
    $all = array_filter($all, fn($p) => ($p['price'] ?? 0) <= $max);
}

// ── Re-index after filters ───────────────────────────────────
$all = array_values($all);

// ── Sort ─────────────────────────────────────────────────────
$sort = $_GET['sort'] ?? '';
match ($sort) {
    'price_asc'  => usort($all, fn($a,$b) => $a['price'] <=> $b['price']),
    'price_desc' => usort($all, fn($a,$b) => $b['price'] <=> $a['price']),
    'name_asc'   => usort($all, fn($a,$b) => strcmp($a['name'], $b['name'])),
    'newest'     => usort($all, fn($a,$b) => strcmp($b['createdAt'] ?? '', $a['createdAt'] ?? '')),
    default      => null,
};

// ── Pagination ───────────────────────────────────────────────
$total = count($all);
$page  = max(1, (int)($_GET['page']  ?? 1));
$limit = min(50, max(1, (int)($_GET['limit'] ?? 20)));
$pages = (int)ceil($total / $limit);
$all   = array_slice($all, ($page - 1) * $limit, $limit);

success([
    'products'   => $all,
    'total'      => $total,
    'page'       => $page,
    'totalPages' => $pages,
    'count'      => count($all),
]);
