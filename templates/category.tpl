{extends file="layout.tpl"}

{block name=content}
    <h1>{$category.name|escape}</h1>
    <p>{$category.description|escape}</p>

    <p>
        <a href="index.php?page=category&id={$category.id|escape}&sort=date">По дате</a>
        <a href="index.php?page=category&id={$category.id|escape}&sort=views">По просмотрам</a>
    </p>

    {foreach $articles as $article}
        <article>
            <h2>
                <a href="index.php?page=article&id={$article.id|escape}">
                    {$article.title|escape}
                </a>
            </h2>
            <p>{$article.description|escape}</p>
            <p>{$article.published_at|escape}, просмотры: {$article.views|escape}</p>
        </article>
    {foreachelse}
        <p>В этой категории пока нет статей.</p>
    {/foreach}

    {if $pages > 1}
        <nav>
            {for $number=1 to $pages}
                {if $number == $page}
                    <span>{$number}</span>
                {else}
                    <a href="index.php?page=category&id={$category.id|escape}&sort={$sort|escape}&p={$number}">{$number}</a>
                {/if}
            {/for}
        </nav>
    {/if}
{/block}