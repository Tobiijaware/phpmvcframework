<?php

require_once __DIR__ . '/../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

use app\core\application;
use app\controllers\SiteController;

$config = [
   'db' => [
       'dsn' => $_ENV['DB_DSN'],
       'user' => $_ENV['DB_USER'],
       'password' => $_ENV['DB_PASSWORD'],
   ]
];

$app = new Application(dirname(__DIR__), $config);
$app->router->get('/', [SiteController::class, 'home']);
$app->router->get('/contact', [SiteController::class, 'contact']);
$app->router->post('/contact',[SiteController::class, 'handleContact']);
$app->run();