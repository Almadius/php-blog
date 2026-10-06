<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\Category;
use App\View;

final class CategoryController
{
    private const PER_PAGE = 5;

    public function __construct(
        private Category $categories,
        private View $view,
    ) {
    }

    public function show(int $id, string $sort, int $page): void
    {
        $category = $this->categories->find($id);

        if ($category === null) {
            http_response_code(404);
            echo 'Категория не найдена';
            return;
        }

        $sort = $sort === 'views' ? 'views' : 'date';
        $total = $this->categories->countArticles($id);
        $pages = max(1, (int) ceil($total / self::PER_PAGE));
        $page = min(max(1, $page), $pages);
        $offset = ($page - 1) * self::PER_PAGE;

        $this->view->render('category.tpl', [
            'title' => $category['name'],
            'category' => $category,
            'articles' => $this->categories->articles($id, $sort, self::PER_PAGE, $offset),
            'sort' => $sort,
            'page' => $page,
            'pages' => $pages,
        ]);
    }
}