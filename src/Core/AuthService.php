<?php
namespace News\Core;

use News\Models\UserModel;

class AuthService
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->userModel = new UserModel($connection);

        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }

 public function login(string $email, string $password): bool
{
    $user = $this->userModel->getUserByEmail($email);

    // Ellenőrizd, hogy van-e találat
    if (empty($user)) {
        return false;
    }

    $user = $user[0];

    // Ellenőrizd a jelszót
    if (password_verify($password, $user['password'])) {
        $_SESSION['user'] = [
            'id' => $user['user_id'],
            'email' => $user['email'],
            'role' => $user['role']
        ];
        return true;
    }

    return false;
}

    public function logout(): void
    {
        session_destroy();
        header("Location: /");
        exit;
    }

    public function isAuthenticated(): bool
    {
        return isset($_SESSION['user']);
    }

    public function isAdmin(): bool
    {
        return $this->isAuthenticated() && $_SESSION['user']['role'] === 'admin';
    }

    public function getUser(): ?array
    {
        return $_SESSION['user'] ?? null;
    }
}