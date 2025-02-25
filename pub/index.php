<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/autoload.php';

use code\Repositories\Category;
use code\Repositories\Product;

$categoryObj = new Category();
$productCollection = new Product();

$categories = $categoryObj->getAllWithProductCount();
$products = $productCollection->getAll();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Категорії та товари</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <div class="col-3">
            <h4>Категорії</h4>
            <ul id="category-list">
                <?php foreach ($categories as $category): ?>
                    <li>
                        <a href="#" class="category-item" data-id="<?= $category['id'] ?>">
                            <?= $category['name'] ?> (<?= $category['product_count'] ?>)
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="col-9">
            <h4>Товары</h4>
            <select id="sort">
                <option value="price_asc">Спочатку дешевше</option>
                <option value="name_asc">За абеткою</option>
                <option value="newest">Спочатку нові</option>
            </select>
            <div id="product-list" class="row"></div>
        </div>
    </div>
</div>

<!-- Modal window -->
<div class="modal fade" id="buyModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Купити товар</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p id="modal-product-name"></p>
                <p id="modal-product-price"></p>
                <button type="button" class="btn btn-success" data-bs-dismiss="modal">У кошик</button>
            </div>
        </div>
    </div>
</div>

<script src="assets/web/js/script.js"></script>
<link href="assets/web/css/page.css" rel="stylesheet">

</body>
</html>
