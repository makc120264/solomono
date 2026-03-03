<?php
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

    // Group by parent_id
    $grouped = [];
    foreach ($categories as $cat) {
        $grouped[$cat['parent_id']][] = $cat['categories_id'];
    }

    /**
     * Recursive function for constructing a tree
     * 
     * @param int $parentId
     * @param array $grouped
     * @return array|int
     */
    function buildCategoryTree(int $parentId, array &$grouped): int|array
    {
        if (!isset($grouped[$parentId])) {
            return $parentId;
        }

        $result = [];
        foreach ($grouped[$parentId] as $childId) {
            $result[$childId] = buildCategoryTree($childId, $grouped);
        }

        return $result;
    }

    // Let's start from the top level (parent_id = 0)
    $tree = [];
    if (isset($grouped[0])) {
        foreach ($grouped[0] as $topId) {
            $tree[$topId] = buildCategoryTree($topId, $grouped);
        }
    }

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
