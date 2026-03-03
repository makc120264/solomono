<?php

namespace code\Repositories;


use code\Interfaces\EntityInterface;
use etc\Database;
use PDO;

class Category implements EntityInterface
{

    /**
     * @var PDO
     */
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    /**
     * @return array
     */
    public function getAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM categories");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @return array
     */
    public function getAllWithProductCount(): array
    {
        $stmt = $this->pdo->query("
            SELECT c.id, c.name, COUNT(p.id) AS product_count
            FROM categories c
            LEFT JOIN products p ON c.id = p.category_id
            GROUP BY c.id
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function getById(int $id): mixed
    {
        $stmt = $this->pdo->prepare("SELECT * FROM categories WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * @param array $data
     * @return bool
     */
    public function create(array $data): bool
    {
        $stmt = $this->pdo->prepare("INSERT INTO categories (name) VALUES (?)");
        return $stmt->execute([$data['name']]);
    }

    /**
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data): bool
    {
        $stmt = $this->pdo->prepare("UPDATE categories SET name = ? WHERE id = ?");
        return $stmt->execute([$data['name'], $id]);
    }

    /**
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM categories WHERE id = ?");
        return $stmt->execute([$id]);
    }

    /**
     * Builds a category tree using an iterative approach.
     *
     * @param array $categories Array of categories with 'categories_id' and 'parent_id'
     * @return array
     */
    public function buildTree(array $categories): array
    {
        $tree = [];
        $refs = [];
        
        foreach ($categories as $cat) {
            $id = $cat['categories_id'];
            $refs[$id] = [];
        }

        foreach ($categories as $cat) {
            $id = $cat['categories_id'];
            $parentId = $cat['parent_id'];
            if ($parentId == 0) {
                $tree[$id] = &$refs[$id];
            } else {
                if (isset($refs[$parentId])) {
                    $refs[$parentId][$id] = &$refs[$id];
                }
            }
        }

        foreach ($refs as $id => &$children) {
            if (empty($children)) {
                $children = $id;
            }
        }

        return $tree;
    }

    /**
     * Builds a category tree using recursion.
     *
     * @param array $categories Array of categories with 'categories_id' and 'parent_id'
     * @return array
     */
    public function buildTreeRecursive(array $categories): array
    {
        $grouped = [];
        foreach ($categories as $cat) {
            $grouped[$cat['parent_id']][] = $cat['categories_id'];
        }

        $tree = [];
        if (isset($grouped[0])) {
            foreach ($grouped[0] as $id) {
                $tree[$id] = $this->buildBranch($id, $grouped);
            }
        }

        return $tree;
    }

    /**
     * Recursive helper to build a branch.
     *
     * @param int $parentId
     * @param array $grouped
     * @return array|int
     */
    private function buildBranch(int $parentId, array &$grouped): array|int
    {
        if (!isset($grouped[$parentId])) {
            return $parentId;
        }

        $result = [];
        foreach ($grouped[$parentId] as $childId) {
            $result[$childId] = $this->buildBranch($childId, $grouped);
        }

        return $result;
    }
}