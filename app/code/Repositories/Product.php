<?php

namespace code\Repositories;

use code\Interfaces\EntityInterface;
use etc\Database;
use PDO;

class Product implements EntityInterface
{
    /**
     * @var PDO
     */
    private PDO $pdo;

    public function __construct() {
        $this->pdo = Database::getConnection();
    }

    /**
     * @param $category_id
     * @param string $sort
     * @return array
     */
    public function getAll($category_id = null, string $sort = 'price_asc'): array
    {
        $sql = "SELECT * FROM products";
        $params = [];

        if ($category_id) {
            $sql .= " WHERE category_id = ?";
            $params[] = $category_id;
        }

        $sql .= match ($sort) {
            'name_asc' => " ORDER BY name ASC",
            'newest' => " ORDER BY created_at DESC",
            default => " ORDER BY price ASC",
        };

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function getById(int $id): mixed
    {
        $stmt = $this->pdo->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * @param array $data
     * @return bool
     */
    public function create(array $data): bool
    {
        $stmt = $this->pdo->prepare("INSERT INTO products (category_id, name, price) VALUES (?, ?, ?)");
        return $stmt->execute([$data['category_id'], $data['name'], $data['price']]);
    }

    /**
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data): bool
    {
        $stmt = $this->pdo->prepare("UPDATE products SET category_id = ?, name = ?, price = ? WHERE id = ?");
        return $stmt->execute([$data['category_id'], $data['name'], $data['price'], $id]);
    }

    /**
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM products WHERE id = ?");
        return $stmt->execute([$id]);
    }
}