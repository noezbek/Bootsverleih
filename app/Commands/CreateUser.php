<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Models\DBConnection;

class CreateUser extends BaseCommand
{
    protected $group       = 'Auth';
    protected $name        = 'user:create';
    protected $description = 'Legt einen neuen User an';

    protected $arguments = [
        'username' => 'Username',
        'password' => 'Passwort',
    ];

    public function run(array $params)
    {
        $username = $params[0] ?? null;
        $password = $params[1] ?? null;

        if (! $username || ! $password) {
            CLI::error('Usage: php spark user:create <username> <password>');
            return;
        }

        $pdo = DBConnection::getConnection();
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("
            INSERT INTO users (username, password_hash, active)
            VALUES (:u, :p, 1)
        ");

        $stmt->execute([
            'u' => $username,
            'p' => $hash
        ]);

        CLI::write("✅ User {$username} angelegt", 'green');
    }
}
