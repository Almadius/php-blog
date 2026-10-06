<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\Article;
use App\Model\Category;
use App\View;

final class HomeController
{
    public function __construct(
        private Category $categories,
        private Article $articles,
        private View $view,
    ) {
    }

    public function index(): void
    {
        $sections = [];

        foreach ($this->categories->withArticles() as $category) {
            $category['articles'] = $this->articles->latestInCategory((int) $category['id']);
            $sections[] = $category;
        }

        $this->view->render('home.tpl', [
            'title' => 'Блог',
            'sections' => $sections,
        ]);
    }
}