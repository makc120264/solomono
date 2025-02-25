<?php

namespace etc;

use PDO;

class Database
{
    private static PDO $connection;

    /**
     * @return PDO
     */
    public static function getConnection(): PDO
    {
        $config = include 'env.php';
        self::$connection = new PDO(
            "mysql:host={$config['db']['host']};dbname={$config['db']['dbname']}",
            $config['db']['user'],
            $config['db']['password']
        );
        self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return self::$connection;
    }
}