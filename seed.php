<?php
// ================================================================
//  seed.php  —  Run once to populate the products collection
//  Usage: php seed.php
// ================================================================

require_once __DIR__ . '/lib/MongoDB.php';

$products = getCollection('products');

// Clear existing products
$products->deleteMany([]);

$items = [
    ['name'=>'Black Kurtha',              'category'=>'kurtha',   'price'=>499,  'stock'=>20, 'image'=>'https://images.meesho.com/images/products/191005346/mocgn_512.avif',                            'description'=>'Elegant black kurtha with embroidery'],
    ['name'=>'Grey Anarkali',             'category'=>'anarkali', 'price'=>799,  'stock'=>15, 'image'=>'https://images.meesho.com/images/products/85578135/eskxb_512.avif',                             'description'=>'Flowing grey anarkali suit'],
    ['name'=>'Purple Lehanga',            'category'=>'lehanga',  'price'=>1299, 'stock'=>10, 'image'=>'https://images.meesho.com/images/products/518584742/ki9o1_512.avif',                            'description'=>'Stunning purple lehanga choli'],
    ['name'=>'Black & White Shirts',      'category'=>'shirts',   'price'=>399,  'stock'=>30, 'image'=>'https://images.meesho.com/images/products/440353953/pqvdn_512.avif',                            'description'=>'Classic black and white printed shirts'],
    ['name'=>'Black & White T-Shirt',     'category'=>'t-shirts', 'price'=>299,  'stock'=>40, 'image'=>'https://images.meesho.com/images/products/72764209/nfvaw_512.avif',                             'description'=>'Casual black and white t-shirt'],
    ['name'=>'Pink Palazoo',              'category'=>'palazoo',  'price'=>349,  'stock'=>25, 'image'=>'https://images.meesho.com/images/products/460732748/ghxod_512.avif',                            'description'=>'Comfortable pink palazoo pants'],
    ['name'=>'Blue Jeans',                'category'=>'jeans',    'price'=>899,  'stock'=>20, 'image'=>'https://images.meesho.com/images/products/630479450/wfgjk_512.avif',                            'description'=>'Slim fit blue denim jeans'],
    ['name'=>'White Jacket',              'category'=>'jacket',   'price'=>1199, 'stock'=>12, 'image'=>'https://images.meesho.com/images/products/194904846/u4kfi_512.avif',                            'description'=>'Stylish white casual jacket'],
    ['name'=>'Black Satin Saree',         'category'=>'sarees',   'price'=>1499, 'stock'=>8,  'image'=>'https://images.cbazaar.com/images/black-satin-blend-embroidered-saree-sasnf6921-u.jpg',        'description'=>'Elegant black satin blend embroidered saree'],
    ['name'=>'Red 3 Piece Set',           'category'=>'sets',     'price'=>999,  'stock'=>18, 'image'=>'https://m.media-amazon.com/images/I/610InQb9rTL._SX425_.jpg',                                  'description'=>'Vibrant red 3 piece matching set'],
    ['name'=>'Brown 3 Piece Set',         'category'=>'sets',     'price'=>999,  'stock'=>14, 'image'=>'https://m.media-amazon.com/images/I/713jWuxPG6L._SX569_.jpg',                                  'description'=>'Trendy brown 3 piece set'],
    ['name'=>'Blue Kurtha',               'category'=>'kurtha',   'price'=>549,  'stock'=>22, 'image'=>'https://m.media-amazon.com/images/I/61VP2fi9WdL._SX425_.jpg',                                  'description'=>'Bright blue printed kurtha'],
    ['name'=>'Hooded Jacket for Girls',   'category'=>'jacket',   'price'=>1099, 'stock'=>16, 'image'=>'https://m.media-amazon.com/images/I/61lAa-BI+lL._SY550_.jpg',                                  'description'=>'Warm hooded jacket for girls'],
    ['name'=>'White Cotton Straight Kurtha','category'=>'kurtha', 'price'=>449,  'stock'=>30, 'image'=>'https://m.media-amazon.com/images/I/81IQzobx04L._SY741_.jpg',                                  'description'=>'Pure white cotton straight-cut kurtha'],
    ['name'=>'Salwar Suit Set',           'category'=>'sets',     'price'=>1199, 'stock'=>10, 'image'=>'https://m.media-amazon.com/images/I/7147-ybFc9L._AC_UF480,600_SR480,600_.jpg',                 'description'=>'Complete salwar suit with dupatta'],
    ['name'=>'Dark Blue 3 Piece Set',     'category'=>'sets',     'price'=>1099, 'stock'=>9,  'image'=>'https://m.media-amazon.com/images/I/71YfSAKLjbL._AC_UF480,600_SR480,600_.jpg',                 'description'=>'Stylish dark blue 3 piece combo'],
    ['name'=>'Green Kurthi',              'category'=>'kurtha',   'price'=>479,  'stock'=>28, 'image'=>'https://m.media-amazon.com/images/I/619-+oTdlUL._SX425_.jpg',                                  'description'=>'Fresh green printed kurthi'],
    ['name'=>'Purple Cotton Anarkali',    'category'=>'anarkali', 'price'=>849,  'stock'=>12, 'image'=>'https://images-eu.ssl-images-amazon.com/images/I/61EMKa9gpwL._AC_UL165_SR165,165_.jpg',        'description'=>'Purple cotton printed anarkali'],
];

$count = 0;
foreach ($items as $item) {
    $products->insertOne($item);
    $count++;
}

echo "✅ Seeded $count products into the 'products' collection.\n";
echo "📂 Saved to: collections/products.json\n";
echo "\nRun the server next:\n  php -S localhost:8000\n";
