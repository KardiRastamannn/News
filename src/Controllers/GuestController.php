<?php

namespace News\Controllers;
use News\Core\Connection;
use News\Models\NewsModel;
use News\Core\AuthService;

class GuestController
{
    private NewsModel $newsModel;
    private AuthService $auth;

    public function __construct(Connection $Connection){
        $this->newsModel = new NewsModel($Connection);
        $this->auth = new AuthService($Connection);
    }
    // Homepage betöltése
    public function showHomePage(){
 
        $user = $this->auth->getUser();
        $content = renderView('guest_home', [
            'news' => $this->newsModel->getPublishedNews(),
            'user'  => $user,
        ]);

        if (isAjaxRequest()) return $content;    

        return renderLayout($content, [
            'user' => $user,
            'extraCss' => '',
        ]);
    }
    // Hír részleteinek betöltése
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
    
        if (isAjaxRequest()) return $content;
      
        return renderLayout($content, [
            'user' => $this->auth->getUser(),
            'extraCss' => '',
        ]);
    }
}