<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{$title|escape}</title>
    <link rel="stylesheet" href="/assets/css/main.css">
</head>
<body>
    <header>
        <div class="wrap">
            <a href="index.php">Блог</a>
        </div>
    </header>
    <main>
        {block name=content}{/block}
    </main>
</body>
</html>