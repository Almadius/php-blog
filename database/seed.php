<?php

declare(strict_types=1);

use App\Database;

require __DIR__ . '/../vendor/autoload.php';

$config = require __DIR__ . '/../config/database.php';
$pdo = (new Database())->connect($config);

$pdo->exec('SET FOREIGN_KEY_CHECKS = 0');
$pdo->exec('TRUNCATE TABLE article_category');
$pdo->exec('TRUNCATE TABLE articles');
$pdo->exec('TRUNCATE TABLE categories');
$pdo->exec('SET FOREIGN_KEY_CHECKS = 1');

$categories = [
    ['name' => 'PHP', 'description' => 'Заметки о языке и о том, как устроен простой блог.'],
    ['name' => 'MySQL', 'description' => 'Запросы, связи таблиц и сортировка.'],
    ['name' => 'Smarty', 'description' => 'Шаблоны страниц и вывод данных.'],
    ['name' => 'Docker', 'description' => 'Окружение, в котором сайт запускается одинаково.'],
];

$articles = [
    ['title' => 'Зачем блогу три таблицы', 'description' => 'Категория, статья и связь между ними.', 'body' => "Одна статья может лежать в нескольких категориях.\nПоэтому связь хранится отдельной таблицей.", 'views' => 120, 'published_at' => '2026-03-20 10:00:00', 'categories' => ['PHP', 'MySQL']],
    ['title' => 'PDO без собранных запросов', 'description' => 'Значения из адреса не склеиваются в SQL.', 'body' => "Плейсхолдер передаёт значение отдельно от текста запроса.\nСортировка выбирается из короткого списка в коде.", 'views' => 80, 'published_at' => '2026-03-18 09:00:00', 'categories' => ['PHP']],
    ['title' => 'Где заканчивается контроллер', 'description' => 'Контроллер не пишет SQL и не собирает HTML.', 'body' => "Он получает данные у модели и отдаёт их в шаблон.\nТак страницу можно читать отдельно от запросов.", 'views' => 45, 'published_at' => '2026-03-15 12:00:00', 'categories' => ['PHP']],
    ['title' => 'Что показывает главная', 'description' => 'Только категории, в которых уже есть статьи.', 'body' => "Для каждой такой категории берутся три последние статьи.\nДата публикации лежит в отдельном поле.", 'views' => 30, 'published_at' => '2026-03-12 15:00:00', 'categories' => ['PHP', 'Smarty']],
    ['title' => 'Пять статей на страницу', 'description' => 'Пагинация считает смещение, а не режет массив в PHP.', 'body' => "Сначала считается общее число статей.\nПотом в запрос уходят limit и offset.", 'views' => 64, 'published_at' => '2026-03-10 11:00:00', 'categories' => ['PHP']],
    ['title' => 'Индекс под сортировку', 'description' => 'Дата и просмотры используются в ORDER BY.', 'body' => "На эти поля поставлены индексы.\nСортировка на странице категории переключает только направление внутри белого списка.", 'views' => 210, 'published_at' => '2026-03-08 08:30:00', 'categories' => ['MySQL']],
    ['title' => 'Связь многие-ко-многим', 'description' => 'Одна статья, две категории, две строки в связующей таблице.', 'body' => "Первичный ключ этой таблицы составной.\nПовторить ту же пару статья-категория нельзя.", 'views' => 95, 'published_at' => '2026-03-06 14:00:00', 'categories' => ['MySQL', 'PHP']],
    ['title' => 'Похожие статьи', 'description' => 'Общая категория делает статьи похожими.', 'body' => "Чем больше общих категорий, тем выше статья в блоке.\nТекущая статья в этот список не входит.", 'views' => 150, 'published_at' => '2026-03-04 16:00:00', 'categories' => ['MySQL']],
    ['title' => 'Общий каркас шаблона', 'description' => 'Шапка описана один раз.', 'body' => "Страницы подключают layout и заполняют один блок.\nПовторять html, head и header в каждом файле не нужно.", 'views' => 25, 'published_at' => '2026-03-02 10:00:00', 'categories' => ['Smarty']],
    ['title' => 'Экранирование в шаблоне', 'description' => 'Текст статьи выводится как текст.', 'body' => "Модификатор escape ставится раньше nl2br.\nИначе перенос строки превратится в видимый тег.", 'views' => 70, 'published_at' => '2026-02-26 13:00:00', 'categories' => ['Smarty', 'PHP']],
    ['title' => 'Пустая категория на главной', 'description' => 'Категория без статей в список не попадает.', 'body' => "Для этого хватает EXISTS по таблице связи.\nОтдельным запросом считать статьи не нужно.", 'views' => 12, 'published_at' => '2026-02-20 09:30:00', 'categories' => ['Smarty']],
    ['title' => 'Одинаковое окружение', 'description' => 'PHP и MySQL поднимаются вместе.', 'body' => "Конфиг приложения читает хост базы из одного файла.\nНа другой машине меняется окружение, а не код страниц.", 'views' => 40, 'published_at' => '2026-02-14 18:00:00', 'categories' => ['Docker', 'MySQL']],
];

$uploads = dirname(__DIR__) . '/public/uploads';

$pdo->beginTransaction();

$categoryIds = [];
$insertCategory = $pdo->prepare(
    'INSERT INTO categories (name, description) VALUES (:name, :description)'
);

foreach ($categories as $category) {
    $insertCategory->execute([
        'name' => $category['name'],
        'description' => $category['description'],
    ]);
    $categoryIds[$category['name']] = (int) $pdo->lastInsertId();
}

$insertArticle = $pdo->prepare(
    'INSERT INTO articles (image, title, description, body, views, published_at)
     VALUES (:image, :title, :description, :body, :views, :published_at)'
);
$attach = $pdo->prepare(
    'INSERT INTO article_category (article_id, category_id)
     VALUES (:article_id, :category_id)'
);

foreach ($articles as $index => $article) {
    $number = $index + 1;
    $filename = 'article-' . $number . '.svg';
    $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="640" height="360">'
        . '<rect width="100%" height="100%" fill="#d9e2ec"/>'
        . '<text x="32" y="64" font-size="28">'
        . htmlspecialchars($article['title'], ENT_XML1)
        . '</text></svg>';
    file_put_contents($uploads . '/' . $filename, $svg);

    $insertArticle->execute([
        'image' => '/uploads/' . $filename,
        'title' => $article['title'],
        'description' => $article['description'],
        'body' => $article['body'],
        'views' => $article['views'],
        'published_at' => $article['published_at'],
    ]);
    $articleId = (int) $pdo->lastInsertId();

    foreach ($article['categories'] as $name) {
        $attach->execute([
            'article_id' => $articleId,
            'category_id' => $categoryIds[$name],
        ]);
    }
}

$pdo->commit();

echo 'Сидер заполнил базу.' . PHP_EOL;