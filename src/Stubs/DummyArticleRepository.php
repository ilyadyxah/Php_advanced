<?php

namespace App\Stubs;

use App\Entities\Article\Article;
use App\Entities\EntityInterface;
use App\Exceptions\ArticleNotFoundException;
use App\Repositories\ArticleRepositoryInterface;

class DummyArticleRepository implements ArticleRepositoryInterface
{

    public function save(EntityInterface $entity)
    {
        // TODO: Implement save() method.
    }

    public function getId(EntityInterface $entity)
    {
        return new Article(
            '1',
            "title" . uniqid(),
            'content' . uniqid()
        );
    }

    public function get(int $id): EntityInterface
    {
        throw new ArticleNotFoundException('Not found');
    }
}