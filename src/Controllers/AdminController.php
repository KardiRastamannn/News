<?php

namespace News\Controllers;

use News\Core\Connection;
use News\Core\AuthService;

class AdminController
{
    private AuthService $auth;

    public function __construct(Connection $connection)
    {
       // session_start();
        $this->auth = new AuthService($connection);
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }

    public function logout()
    {
        session_destroy();
    
      
    
        // sima redirect
        header('Location: /');
        exit;
    
    }

    public function showDashboard() {
        if (!$this->auth->isAuthenticated() || !$this->auth->isAdmin()) {
            header("Location: /");
            exit;
        }

        $content = renderView('admin_dashboard', [
            'user'  => $this->auth->getUser(),
        ]);

        // AJAX kérés esetén csak a tartalom térjen vissza
        if (isAjaxRequest()) return $content;

        // Normál oldalbetöltés esetén jöhet a layout

        return renderLayout($content, [
            'user' => $this->auth->getUser(),
            'extraCss' => '',
        ]);
    }

    public function login(){
        if ($this->auth->isAuthenticated()) {
            header("Location: /admin/dashboard");
            exit;
        }
        
        $error = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
        
            if ($this->auth->login($email, $password)) {
                header("Location: /admin/dashboard");
                exit;
            } else {
                $error = "Hibás e-mail vagy jelszó!";
            }
        }

        $content = renderView('login', [
            'user'  => $this->auth->getUser(),
        ]);

        // AJAX kérés esetén csak a tartalom térjen vissza
        if (isAjaxRequest()) return $content;

        // Normál oldalbetöltés esetén jöhet a layout
    
        return renderLayout($content, [
            'user' => $this->auth->getUser(),
            'extraCss' => '',
        ]);

    }

}