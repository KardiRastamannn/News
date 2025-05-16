<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/Core/HelperFunctions.php'; // Globális függvények betöltése

session_start();

// Konténer létrehozása és konfigurálása
$container = new News\Core\Container();
$container->addInjections();

// Router létrehozása
$router = $container->resolve(News\Core\Router::class);
$routes = require __DIR__ . '/../routes/web.php';

// Route-ok regisztrálása
foreach ($routes as [$path, $handler]) {
    $router->add($path, $handler);
}
// Hibakezelés
$router->addErrorHandler(404, function() {
    
    echo renderView('404');
   
});

// Kérés feldolgozása
$url = $_SERVER['REQUEST_URI'] ?? '/';
$url = parse_url($url, PHP_URL_PATH);
echo $router->dispatch($url);