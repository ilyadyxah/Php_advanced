<?php

namespace Tests\Commands;

use App\Commands\CreateArticleCommand;
use App\Entities\Article\Article;
use App\Exceptions\CommandException;
use App\Stubs\DummyArticleRepository;
use PHPUnit\Framework\TestCase;

class CreateCommandTest extends TestCase
{
    public function testItThrowsAnExceptionWhenArticleAlreadyExists(): void
    {
        $createCommand = new CreateArticleCommand(new DummyArticleRepository());

        $this->expectException(CommandException::class);
        $this->expectExceptionMessage('Article already exist');

        $article = new Article(
            '2',
            'someTitle123',
            'someText'
        );

        $createCommand->handle($article);
    }
}