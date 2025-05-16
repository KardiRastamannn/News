<?php
// Csak a view kierendelése layout nélkül
function renderView(string $view, array $data = []): string{
    extract($data);
    ob_start();
    require __DIR__ . '/../Views/' . $view . '.php';
    return ob_get_clean();
}
// Layout kirenderelése
function renderLayout(string $content, array $data = []): string{
    extract($data);
    ob_start();
    require __DIR__ . '/../Views/layout.php';
    return ob_get_clean();
}
// Ez dönti el, hogy AJAXOS a kérés, vagy nem.
function isAjaxRequest(): bool {
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
}
// Kép mentése fizikálisan
function saveImage() {
    $imagePath = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $tmpName = $_FILES['image']['tmp_name'];
        $originalName = basename($_FILES['image']['name']);
        $newPath =  'uploads/'.uniqid() . '-' . $originalName;
        move_uploaded_file($tmpName, $newPath);

        // Ezt eltárolhatod az adatbázisba:
       return $imagePath = $newPath;
    }
}