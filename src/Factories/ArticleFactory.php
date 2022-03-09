<?php

namespace App\Factories;

use App\Decorator\ArticleDecorator;
use App\Entities\Article\Article;
use App\Entities\Article\ArticleInterface;
use JetBrains\PhpStorm\Pure;

final class ArticleFactory implements ArticleFactoryInterface
{
    #[Pure] public function create(ArticleDecorator $articleDecorator): ArticleInterface
    {
        return new Article(
            $articleDecorator->authorId,
            $articleDecorator->title,
            $articleDecorator->text,
        );
    }
}