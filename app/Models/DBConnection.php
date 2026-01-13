<?php


namespace App\Models;

use Exception;
use PDO;

class DBConnection
{
    private static ?PDO $pdo = null;
    private static array $env = [];

    private function __construct()
    {
    }

    private static function loadEnv(): void
    {
        if (!empty(self::$env))
            return;

        $path = ROOTPATH . '.env';


        if (!file_exists($path))
            throw new Exception(".env Datei nicht gefunden");

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '' || str_starts_with($line, '#'))
                continue;

            [$key, $value] = explode('=', $line, 2);
            self::$env[trim($key)] = trim($value);
        }
    }

    public static function getConnection(): PDO
    {
        if (self::$pdo === null) {
            self::loadEnv();

            $host = self::$env['DB_HOST'];
            $db = self::$env['DB_NAME'];
            $user = self::$env['DB_USER'];
            $pass = self::$env['DB_PASS'];
            $charset = self::$env['DB_CHARSET'] ?? 'utf8mb4';

            $dsn = "mysql:host=$host;dbname=$db;charset=$charset";

            self::$pdo = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        }

        return self::$pdo;
    }
}
