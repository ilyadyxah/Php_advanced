<?php

namespace Tests\Repositories;

use App\Commands\CreateArticleCommand;
use App\config\SqlLiteConfig;
use App\Connections\ConnectorInterface;
use App\Drivers\Connection;
use App\Drivers\PdoConnectionDriver;
use App\Entities\Article\Article;
use App\Exceptions\ArticleNotFoundException;
use App\Exceptions\CommandException;
use App\Repositories\ArticleRepository;
use PDOStatement;
use PHPUnit\Framework\TestCase;

class ArticleRepositoryTest extends TestCase
{
    public function testItThrowsAnExceptionWhenArticleNotFound(): void
    {
        $statementStub = $this->createStub(PDOStatement::class);
        $statementStub->method('fetch')->willReturn(false);

        $repository = new ArticleRepository($this->getConnectionStub()->getConnection());

        $this->expectException(ArticleNotFoundException::class);
        $this->expectExceptionMessage('Cannot find article');

        $repository->get('432321');
    }

    public function testItSavesArticleToDatabase(): void
    {
        $repository = new ArticleRepository($this->getConnectionStub()->getConnection());
        $createCommand = new CreateArticleCommand($repository);

        $this->expectException(CommandException::class);
        $this->expectExceptionMessage('Article already exists');

        $entity = new Article(
            1,
            'asd',
            'asd'
        );

        $createCommand->handle($entity);
    }

    private function getConnectionStub(): ConnectorInterface
    {
        return new class implements ConnectorInterface {

            public function getConnection(): Connection
            {
                return PdoConnectionDriver::getInstance(SqlLiteConfig::DSN);
            }
        };
    }


}