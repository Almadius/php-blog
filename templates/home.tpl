<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>{$title|escape}</title>
</head>
<body>
    <h1>Блог</h1>

    {foreach $sections as $section}
        <section>
            <h2>{$section.name|escape}</h2>
            <p>{$section.description|escape}</p>

            {foreach $section.articles as $article}
                <article>
                    <h3>
                        <a href="index.php?page=article&id={$article.id|escape}">
                            {$article.title|escape}
                        </a>
                    </h3>
                    <p>{$article.description|escape}</p>
                </article>
            {/foreach}

            <a href="index.php?page=category&id={$section.id|escape}">Все статьи</a>
        </section>
    {/foreach}
</body>
</html>