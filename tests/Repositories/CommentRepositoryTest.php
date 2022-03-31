<?php

namespace Repositories;

use App\Commands\CreateCommand;
use App\config\SqlLiteConfig;
use App\Connections\ConnectorInterface;
use App\Drivers\Connection;
use App\Drivers\PdoConnectionDriver;
use App\Entities\Comment\Comment;
use App\Exceptions\CommandException;
use App\Exceptions\CommentNotFoundException;
use App\Repositories\CommentRepository;
use PDOStatement;
use PHPUnit\Framework\TestCase;

class CommentRepositoryTest extends TestCase
{
    public function testItThrowsAnExceptionWhenCommentNotFound(): void
    {
        $statementStub = $this->createStub(PDOStatement::class);
        $statementStub->method('fetch')->willReturn(false);

        $repository = new CommentRepository($this->getConnectionStub());

        $this->expectException(CommentNotFoundException::class);
        $this->expectExceptionMessage('Cannot find comment');

        $repository->get('234');
    }

    public function testItSavesCommentToDatabase(): void
    {
        $repository = new CommentRepository($this->getConnectionStub());
        $createCommand = new CreateCommand($repository);

        $this->expectException(CommandException::class);
        $this->expectExceptionMessage('Comment already exists');

        $entity = new Comment(
            1,
            3,
            'someText'
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