<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\Article;
use App\View;

final class ArticleController
{
    public function __construct(
        private Article $articles,
        private View $view,
    ) {
    }

    public function show(int $id): void
    {
        $article = $this->articles->find($id);

        if ($article === null) {
            http_response_code(404);
            echo 'Статья не найдена';
            return;
        }

        $this->articles->addView($id);
        $article['views'] = (int) $article['views'] + 1;

        $this->view->render('article.tpl', [
            'title' => $article['title'],
            'article' => $article,
            'categories' => $this->articles->categories($id),
            'similar' => $this->articles->similar($id),
        ]);
    }
}