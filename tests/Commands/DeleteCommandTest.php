<?php

namespace Commands;

use App\Commands\DeleteCommand;
use App\Entities\Article\Article;
use App\Entities\EntityInterface;
use App\Exceptions\ArticleNotFoundException;
use App\Exceptions\CommandException;
use App\Repositories\ArticleRepositoryInterface;
use PHPUnit\Framework\TestCase;

class DeleteCommandTest extends TestCase
{
    public function testItThrowsAnExceptionWhenArticleNotExists(): void
    {
        $createCommand = new DeleteCommand($this->makeArticleRepository());

        $this->expectException(CommandException::class);
        $this->expectExceptionMessage('Article not exist');

        $article = new Article(
            '2',
            'someTitle123',
            'someText'
        );

        $createCommand->handle($article);
    }

    private function makeArticleRepository(): ArticleRepositoryInterface
    {
        return new class implements ArticleRepositoryInterface {

            public function getId(EntityInterface $entity)
            {
                return false;
            }

            public function save(EntityInterface $entity)
            {
                // TODO: Implement save() method.
            }

            public function get(int $id): EntityInterface
            {
                throw new ArticleNotFoundException('Not found');
            }
        };
    }
}