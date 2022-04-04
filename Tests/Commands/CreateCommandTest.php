<?php

namespace Tests\Commands;

use App\Commands\CreateArticleCommand;
use App\Entities\Article\Article;
use App\Exceptions\CommandException;
use App\Stubs\DummyArticleRepository;
use PHPUnit\Framework\TestCase;
use Tests\Traits\LoggerTrait;

class CreateCommandTest extends TestCase
{
    use LoggerTrait;

    public function testItThrowsAnExceptionWhenArticleAlreadyExists(): void
    {
        $createCommand = new CreateArticleCommand(new DummyArticleRepository(), $this->getLogger());

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