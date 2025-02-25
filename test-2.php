<?php
$config = include __DIR__ . '/app/etc/env.php';

$dsn = "mysql:host={$config['db']['host']};dbname={$config['db']['dbname']}";
$user = $config['db']['user'];
$pass = $config['db']['password'];

try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);

    // Query the DB to get all categories
    $stmt = $pdo->query("SELECT categories_id, parent_id FROM categories_test2");
    $categories = $stmt->fetchAll();

    // Form an array of category tree
    $tree = [];
    foreach ($categories as $category) {
        $tree[$category['categories_id']] = [];
    }

    foreach ($categories as $category) {
        if ($category['parent_id'] > 0) {
            $tree[$category['parent_id']][] = $category['categories_id'];
        }
    }

    // Convert the array to the desired format
    function buildTree(array &$tree): array
    {
        foreach ($tree as $id => &$children) {
            if (empty($children)) {
                $children = $id;
            } else {
                $tree1 = array_combine($children, array_fill(0, count($children), []));
                $children = buildTree($tree1);
            }
        }

        return $tree;
    }

    $result = buildTree($tree);

    // Output the result
    echo "<pre>" . print_r($result, true) . "</pre>";
} catch (PDOException $e) {
    die("Connection error: " . $e->getMessage());
}
