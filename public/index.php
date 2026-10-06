<?php

declare(strict_types=1);

use App\Controller\HomeController;
use App\Database;
use App\Model\Article;
use App\Model\Category;
use App\View;

require __DIR__ . '/../vendor/autoload.php';

$config = require __DIR__ . '/../config/database.php';
$pdo = (new Database())->connect($config);
$page = $_GET['page'] ?? 'home';

if ($page === 'home') {
    (new HomeController(
        new Category($pdo),
        new Article($pdo),
        new View(),
    ))->index();
    return;
}

http_response_code(404);
echo 'Страница не найдена';