<?php
namespace News\Controllers;

use News\Core\Connection;
use News\Models\UserModel;
use News\Core\AuthService;


class UserController
{
    private Connection $connection;
    private UserModel $userModel;
    private AuthService $auth;


    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
        $this->userModel = new UserModel ($connection);
        $this->auth = new AuthService ($connection);

    }

    public function handleRequest(): string
    {
        if (!$this->auth->isAdmin()) {
            header("Location: /");
            exit;
        }
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
           $affectedRows = $this->processPostRequest();
    
            if (isAjaxRequest()) {
                return $affectedRows; // a JS fetch().then(...) ezt várja
            }
    
            header("Location: /admin/users");
            exit;
        }
    
      return $this->showUsers();
    }

    public function showUsers(){
        if (!$this->auth->isAuthenticated() || !$this->auth->isAdmin()) {
            header("Location: /");
            exit;
        }

        $content =  renderView('admin_users', [
            'users'  =>  $this->userModel->getAllUsers(),
        ]);
    
        // AJAX kérés esetén csak a tartalom térjen vissza
        if (isAjaxRequest()) return $content;
    
        // Normál oldalbetöltés esetén jöhet a layout
    
        return renderLayout($content, [
            'user' => $this->auth->getUser(),
            'extraCss' => '',
        ]);
    }

    public function getUserById($id){
        return $user = $this->userModel->getUserById($id);
    }

    private function processPostRequest()
    {
        // CSRF ellenőrzés
        // if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        //     die("Érvénytelen kérés!");
        // }
        if (isset($_POST['delete_user_id'])) {
           return $this->userModel->deleteUser((int)$_POST['delete_user_id']);
        } elseif (!empty($_POST['email']) && !empty($_POST['role'])) {
            $password = $_POST['password'] ?? '';
            if (!empty($_POST['user_id'])) {
                return $this->userModel->updateUser(
                    (int)$_POST['user_id'],
                    $_POST['email'],
                    $_POST['role'],
                    $password
                );
            } else {
                return $this->userModel->createUser(
                    $_POST['email'],
                    $password,
                    $_POST['role']
                );
            }
        }
    }
}