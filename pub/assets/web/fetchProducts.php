<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/autoload.php';

use code\Repositories\Product;

$category_id = isset($_GET['category_id']) ? (int)$_GET['category_id'] : null;
$sort = $_GET['sort'] ?? 'price_asc';

$productCollection = new Product();
$products = $productCollection->getAll($category_id, $sort);

if (!empty($products)) {
    foreach ($products as $product) {
        echo '<div class="col-md-4 col-sm-6 col-12 product-item">';
        echo '<h5>' . htmlspecialchars($product['name']) . '</h5>';
        echo '<p>Цена: ' . number_format($product['price'], 2, ',', ' ') . ' грн.</p>';
        echo '<button class="btn btn-primary buy-btn" 
                 data-name="' . htmlspecialchars($product['name']) . '" 
                 data-price="' . $product['price'] . '">
                 Придбати
              </button>';
        echo '</div>';
    }
} else {
    echo '<div class="col-12"><p>Немає товарів у цій категорії.</p></div>';
}

