<?php

declare(strict_types=1);

namespace App\Model;

use PDO;

final class Article
{
    public function __construct(private PDO $pdo)
    {
    }

    public function latestInCategory(int $categoryId, int $limit = 3): array
    {
        $sql = 'SELECT a.*
                FROM articles a
                INNER JOIN article_category ac ON ac.article_id = a.id
                WHERE ac.category_id = :category_id
                ORDER BY a.published_at DESC
                LIMIT :limit';
        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(':category_id', $categoryId, PDO::PARAM_INT);
        $statement->bindValue(':limit', $limit, PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetchAll();
    }

    public function find(int $id): ?array
    {
        $statement = $this->pdo->prepare(
            'SELECT * FROM articles WHERE id = :id'
        );
        $statement->execute(['id' => $id]);
        $article = $statement->fetch();
        return $article === false ? null : $article;
    }

    public function categories(int $articleId): array
    {
        $statement = $this->pdo->prepare(
            'SELECT c.id, c.name
             FROM categories c
             INNER JOIN article_category ac ON ac.category_id = c.id
             WHERE ac.article_id = :article_id
             ORDER BY c.name'
        );
        $statement->execute(['article_id' => $articleId]);
        return $statement->fetchAll();
    }

    public function addView(int $id): void
    {
        $statement = $this->pdo->prepare(
            'UPDATE articles SET views = views + 1 WHERE id = :id'
        );
        $statement->execute(['id' => $id]);
    }

    public function similar(int $id, int $limit = 3): array
    {
        $sql = 'SELECT a.*, COUNT(*) AS shared_categories
                FROM articles a
                INNER JOIN article_category ac ON ac.article_id = a.id
                WHERE ac.category_id IN (
                    SELECT category_id
                    FROM article_category
                    WHERE article_id = :id
                )
                AND a.id <> :current_id
                GROUP BY a.id
                ORDER BY shared_categories DESC, a.published_at DESC
                LIMIT :limit';
        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(':id', $id, PDO::PARAM_INT);
        $statement->bindValue(':current_id', $id, PDO::PARAM_INT);
        $statement->bindValue(':limit', $limit, PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetchAll();
    }
}