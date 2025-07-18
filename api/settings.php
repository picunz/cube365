<?php
class Settings {
    private static array $data = [];

    public static function load(array $config): void {
        self::$data = array_merge(self::$data, $config);
    }

    public static function set(string $key, mixed $value): void {
        self::$data[$key] = $value;
    }

    public static function get(string $key, mixed $default = null): mixed {
        return self::$data[$key] ?? $default;
    }

    public static function all(): array {
        return self::$data;
    }

    public static function has(string $key): bool {
        return array_key_exists($key, self::$data);
    }

    public static function initDb(array $params): void {
        try {
            $dsn = "mysql:host={$params['host']};dbname={$params['dbname']};charset=utf8mb4";
            $pdo = new PDO($dsn, $params['user'], $params['password'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ]);
            self::set('db', $pdo);
        } catch (PDOException $e) {
            die("Errore connessione DB: " . $e->getMessage());
        }
    }
}