<?php

declare(strict_types=1);

namespace App\Model;

use PDO;

final class Category
{
    public function __construct(private PDO $pdo)
    {
    }

    public function withArticles(): array
    {
        $sql = 'SELECT c.*
                FROM categories c
                WHERE EXISTS (
                    SELECT 1
                    FROM article_category ac
                    WHERE ac.category_id = c.id
                )
                ORDER BY c.name';
    
        return $this->pdo->query($sql)->fetchAll();
    }

    public function find(int $id): ?array
    {
        $statement = $this->pdo->prepare(
            'SELECT * FROM categories WHERE id = :id'
        );
        $statement->execute(['id' => $id]);
        $category = $statement->fetch();

        return $category === false ? null : $category;
    }

    public function articles(int $categoryId, string $sort, int $limit, int $offset): array
    {
        $sql = "SELECT a.*
                FROM articles a
                INNER JOIN article_category ac ON ac.article_id = a.id
                WHERE ac.category_id = :category_id
                ORDER BY {$this->orderBy($sort)}
                LIMIT :limit OFFSET :offset";
    
        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(':category_id', $categoryId, PDO::PARAM_INT);
        $statement->bindValue(':limit', $limit, PDO::PARAM_INT);
        $statement->bindValue(':offset', $offset, PDO::PARAM_INT);
        $statement->execute();
    
        return $statement->fetchAll();
    }

    public function countArticles(int $categoryId): int
    {
        $statement = $this->pdo->prepare(
            'SELECT COUNT(*)
             FROM articles a
             INNER JOIN article_category ac ON ac.article_id = a.id
             WHERE ac.category_id = :category_id'
        );
        $statement->execute(['category_id' => $categoryId]);
    
        return (int) $statement->fetchColumn();
    }

    private function orderBy(string $sort): string
    {
        return match ($sort) {
            'views' => 'a.views DESC',
            default => 'a.published_at DESC',
        };
    }
}