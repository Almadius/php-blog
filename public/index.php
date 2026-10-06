<?php

declare(strict_types=1);

use App\Controller\CategoryController;
use App\Controller\HomeController;
use App\Database;
use App\Model\Article;
use App\Model\Category;
use App\View;

require __DIR__ . '/../vendor/autoload.php';

$config = require __DIR__ . '/../config/database.php';
$pdo = (new Database())->connect($config);

$categories = new Category($pdo);
$articles = new Article($pdo);
$view = new View();
$page = $_GET['page'] ?? 'home';

if ($page === 'home') {
    (new HomeController($categories, $articles, $view))->index();
    return;
}

if ($page === 'category') {
    (new CategoryController($categories, $view))->show(
        (int) ($_GET['id'] ?? 0),
        (string) ($_GET['sort'] ?? 'date'),
        (int) ($_GET['p'] ?? 1),
    );
    return;
}

http_response_code(404);
echo 'Страница не найдена';