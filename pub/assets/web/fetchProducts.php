<?php

require_once __DIR__ . '/../../../autoload.php';

use code\Repositories\Product;

header('Content-Type: application/json');

$category_id = isset($_GET['category_id']) ? (int)$_GET['category_id'] : null;
$sort = $_GET['sort'] ?? 'price_asc';

$productCollection = new Product();
$products = $productCollection->getAll($category_id, $sort);

echo json_encode($products);

