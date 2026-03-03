<?php
require_once __DIR__ . '/../autoload.php';
use code\Repositories\Category;

$startTime = microtime(true);
ob_start();
$config = include __DIR__ . '/../app/etc/env.php';

$dsn = "mysql:host={$config['db']['host']};dbname={$config['db']['dbname']}";
$user = $config['db']['user'];
$pass = $config['db']['password'];

try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);

    // Get all categories
    $stmt = $pdo->query("SELECT categories_id, parent_id FROM categories_test2");
    $categories = $stmt->fetchAll();

    $categoryRepo = new Category();
//    $tree = $categoryRepo->buildTreeRecursive($categories);
    $tree = $categoryRepo->buildTree($categories);

    // Output of the result
//    echo json_encode($tree, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

    $content = ob_get_clean();
    $executionTime = microtime(true) - $startTime;
    echo "Execution time: " . round($executionTime, 4) . " seconds" . PHP_EOL;
    echo $content;

    if (php_sapi_name() === 'cli') {
        print_r($tree);
    } else {
        echo "<pre>" . print_r($tree, true) . "</pre>";
    }

} catch (PDOException $e) {
    die("Connection error: " . $e->getMessage());
}
