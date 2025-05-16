<?php
namespace News\Controllers;
use News\Core\Connection;
use News\Models\NewsModel;
use News\Core\AuthService;

class NewsController
{
    private NewsModel $newsModel;
    private AuthService $auth;


    public function __construct(Connection $connection)
    {
        $this->newsModel = new NewsModel($connection);
        $this->auth = new AuthService($connection);
    }

	public function handleRequest()
    {   
        if (!$this->auth->isAdmin()) {
            header("Location: /");
            exit;
        }
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
           // $user = $this->auth->getUser(); // innen jön a user_id

            $affectedRows = $this->processNewsRequest();
    
            if (isAjaxRequest()) {
                return $affectedRows; // a JS fetch().then(...) ezt várja
            }
    
            header("Location: /admin/users");
            exit;
        }
    
      return $this->showNews();
    }

    public function getNewById($id){
        return $user = $this->newsModel->getNewById($id);
    }

    public function showNews(){
        if (!$this->auth->isAuthenticated() || !$this->auth->isAdmin()) {
            header("Location: /");
            exit;
        }
        // echo '<pre>';
        // var_dump($this->newsModel->getAllNews());die;
        // echo '</pre>';

        $content = renderView('admin_news', [
            'news'  =>  $this->newsModel->getAllNews(),
        ]);
    
        // AJAX kérés esetén csak a tartalom térjen vissza
        if (isAjaxRequest()) return $content;
    
        // Normál oldalbetöltés esetén jöhet a layout
    
        return renderLayout($content, [
            'user' => $this->auth->getUser(),
            'extraCss' => ''
        ]);
    }

	private function processNewsRequest()
    {
        // if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {   improve idea
        //     die("Érvénytelen CSRF token!");
        // }
        
        if (isset($_POST['delete_news_id'])) {
            return $this->newsModel->deleteNew((int)$_POST['delete_news_id']);
        } elseif (isset($_POST['title'], $_POST['content'])) {
            $publishAt = $_POST['publish_at'] ?? null;
            if (!empty($_POST['news_id'])) {
                $imagePath = saveImage();
                return $this->newsModel->updateNew((int)$_POST['news_id'], $_POST['title'], $_POST['content'], $_POST['intro'], $publishAt, $imagePath);
            } else {
                $imagePath = saveImage();
                return $this->newsModel->createNew((int)$_SESSION['user']['id'], $_POST['title'], $_POST['content'], $_POST['intro'], $publishAt, $imagePath);
            }
        }
    }
}
?>