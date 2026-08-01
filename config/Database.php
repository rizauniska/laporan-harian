<?php
declare(strict_types=1);

namespace App\Config;

use PDO;
use PDOException;

class Database
{
    private static ?Database $instance = null;
    private PDO $connection;

    private function __construct()
    {
        $host = 'localhost';
        $dbname = 'kasir_db';
        $user = 'root';
        $password = '';

        try {
            $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];
            $this->connection = new PDO($dsn, $user, $password, $options);
        } catch (PDOException $e) {
            jsonResponse(['success' => false, 'message' => 'Database connection failed: ' . $e->getMessage()], 500);
        }
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection(): PDO
    {
        return $this->connection;
    }

    private function __clone() {}
    public function __wakeup()
    {
        throw new \Exception("Cannot unserialize singleton");
    }
}
