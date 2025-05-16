<?php
namespace News\Models;

use News\Core\Connection;

class UserModel
{
    private Connection $db;

    public function __construct(Connection $connection)
    {
        $this->db = $connection;
    }

    public function getUserByEmail(string $email): ?array
    {
        $stmt = $this->db->pdoSelect(
            "SELECT * FROM users WHERE email = :email LIMIT 1",
            ['email' => $email]
        );
        return $stmt;
    }

    public function getUserById(int $id): ?array
    {
        return $this->db->pdoSelect(
            "SELECT user_id, email, role, is_active FROM users WHERE user_id = :user_id LIMIT 1",
            ['user_id' => $id]
        );
    }

    public function getAllUsers(): array
    {
        return $this->db->pdoSelect("SELECT * FROM users");
    }

    public function updateUser(int $id, string $email, string $role, string $password): ?int
    {
       return $this->db->pdoQuery(
            "UPDATE users SET email = ?, role = ?, password = ? WHERE user_id = ?",
            [$email, $role, password_hash($password, PASSWORD_DEFAULT), $id]
        );
    }

    public function deleteUser(int $id): ?int
    {
        return $this->db->pdoQuery(
            "DELETE FROM users WHERE user_id = ?",
            [$id]
        );
    }

    public function createUser(string $email, string $password, string $role = 'user'): ?int
    {
        return $this->db->pdoQuery(
            "INSERT INTO users (email, password, role) VALUES (?, ?, ?)",
            [$email, password_hash($password, PASSWORD_DEFAULT), $role]
        );
    }
}