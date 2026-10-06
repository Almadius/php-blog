{extends file="layout.tpl"}

{block name=content}
    <article>
        <img src="{$article.image|escape}" alt="{$article.title|escape}">
        <h1>{$article.title|escape}</h1>
        <p>{$article.description|escape}</p>
        <div>{$article.body|escape|nl2br}</div>
        <p>Просмотры: {$article.views|escape}</p>
        <p>
            {foreach $categories as $category}
                <a href="index.php?page=category&id={$category.id|escape}">{$category.name|escape}</a>
            {/foreach}
        </p>
    </article>

    {if $similar}
        <section>
            <h2>Похожие статьи</h2>
            {foreach $similar as $item}
                <article>
                    <h3>
                        <a href="index.php?page=article&id={$item.id|escape}">
                            {$item.title|escape}
                        </a>
                    </h3>
                    <p>{$item.description|escape}</p>
                </article>
            {/foreach}
        </section>
    {/if}
{/block}