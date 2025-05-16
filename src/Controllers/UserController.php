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


    public function __construct(Connection $connection){
        $this->connection = $connection;
        $this->userModel = new UserModel ($connection);
        $this->auth = new AuthService ($connection);

    }

    // Minden kérés ide érkezik be és ez dönti el mi lesz a továbbiakban.
    public function handleRequest(): string{
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

    //Admin által szerkeszthető userek betöltése
    public function showUsers(){
        if (!$this->auth->isAuthenticated() || !$this->auth->isAdmin()) {
            header("Location: /");
            exit;
        }

        $content =  renderView('admin_users', [
            'users'  =>  $this->userModel->getAllUsers(),
        ]);
    
        if (isAjaxRequest()) return $content;
        
        return renderLayout($content, [
            'user' => $this->auth->getUser(),
            'extraCss' => '',
        ]);
    }
    // Egy user lekérdezése
    public function getUserById($id){
        return $user = $this->userModel->getUserById($id);
    }

    private function processPostRequest(){
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