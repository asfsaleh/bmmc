<?php
/**
 * Database Helper Wrapper
 */

require_once __DIR__ . '/../config/database.php';

class Database {
    private PDO $pdo;

    public function __construct() {
        $this->pdo = DB::getConnection();
    }

    public function getPdo(): PDO {
        return $this->pdo;
    }

    public function query(string $sql, array $params = []): PDOStatement {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public function fetch(string $sql, array $params = []): ?array {
        $stmt = $this->query($sql, $params);
        $res = $stmt->fetch();
        return $res ?: null;
    }

    public function fetchAll(string $sql, array $params = []): array {
        $stmt = $this->query($sql, $params);
        return $stmt->fetchAll();
    }

    public function insert(string $table, array $data): int {
        $keys = array_keys($data);
        $fields = implode(', ', $keys);
        $placeholders = implode(', ', array_fill(0, count($keys), '?'));

        $sql = "INSERT INTO {$table} ({$fields}) VALUES ({$placeholders})";
        $this->query($sql, array_values($data));
        return (int)$this->pdo->lastInsertId();
    }

    public function update(string $table, array $data, string $where, array $whereParams = []): int {
        $fields = [];
        $params = [];
        foreach ($data as $key => $value) {
            $fields[] = "{$key} = ?";
            $params[] = $value;
        }
        $fieldsStr = implode(', ', $fields);
        $params = array_merge($params, $whereParams);

        $sql = "UPDATE {$table} SET {$fieldsStr} WHERE {$where}";
        $stmt = $this->query($sql, $params);
        return $stmt->rowCount();
    }

    public function delete(string $table, string $where, array $whereParams = []): int {
        $sql = "DELETE FROM {$table} WHERE {$where}";
        $stmt = $this->query($sql, $whereParams);
        return $stmt->rowCount();
    }
}
