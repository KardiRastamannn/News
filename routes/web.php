<?php

return [
    ['', [\News\Controllers\GuestController::class, 'showHomePage']],
    ['new/{id}', [\News\Controllers\GuestController::class, 'showNew']],
    ['admin/dashboard', [\News\Controllers\AdminController::class, 'showDashboard']],
    ['login', [\News\Controllers\AdminController::class, 'login']],
    ['logout', [\News\Controllers\AdminController::class, 'logout']],
    ['admin/users', [\News\Controllers\UserController::class, 'handleRequest']], //user modify
    ['admin/users/{id}', [\News\Controllers\UserController::class, 'getUserById']],
    ['admin/news', [\News\Controllers\NewsController::class, 'handleRequest']], // new modify
    ['admin/new/{id}', [\News\Controllers\NewsController::class, 'getNewById']],
];
