<?php

namespace News\Controllers;
use News\Core\Connection;
use News\Models\NewsModel;
use News\Core\AuthService;

class GuestController
{
    private NewsModel $newsModel;
    private AuthService $auth;

    public function __construct(Connection $Connection)
    {
        $this->newsModel = new NewsModel($Connection);
        $this->auth = new AuthService($Connection);
    }

    public function showHomePage()
    {
        // echo '<pre>';
        // var_dump($this->newsModel->getPublishedNews());die;
        // echo '</pre>';

        $user = $this->auth->getUser();
        $content = renderView('guest_home', [
            'news' => $this->newsModel->getPublishedNews(),
            'user'  => $user,
        ]);
    
        // AJAX kérés esetén csak a tartalom térjen vissza
        if (isAjaxRequest()) return $content;

        // Normál oldalbetöltés esetén jöhet a layout
      
        return renderLayout($content, [
            'user' => $user,
            'extraCss' => '',
        ]);
    }

    public function showNew(int $id)
    {
        $new = $this->newsModel->getPublishedNewById($id);
        if (!$new) {
            http_response_code(404);
            die("A keresett blogposzt nem található.");
        }
        $content = renderView('guest_new', [
            'new'  => $new,
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