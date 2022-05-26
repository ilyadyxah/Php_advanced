<?php

namespace Tests\Commands;

use App\Commands\DeleteArticleCommand;
use App\Entities\Article\Article;
use App\Entities\EntityInterface;
use App\Exceptions\ArticleNotFoundException;
use App\Exceptions\CommandException;
use App\Repositories\ArticleRepositoryInterface;
use PHPUnit\Framework\TestCase;
use Tests\Traits\LoggerTrait;

class DeleteCommandTest extends TestCase
{
    public function testItThrowsAnExceptionWhenArticleNotExists(): void
    {
        $createCommand = new DeleteArticleCommand($this->makeArticleRepository());

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

            public function findById(int $id): EntityInterface
            {
                throw new ArticleNotFoundException('Not found');
            }
        };
    }
}