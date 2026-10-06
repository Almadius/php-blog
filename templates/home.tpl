{extends file="layout.tpl"}

{block name=content}
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

            <a class="button" href="index.php?page=category&id={$section.id|escape}">Все статьи</a>
        </section>
    {/foreach}
{/block}