<?php

namespace App\Http\Actions\Article;

use App\Commands\CreateArticleCommand;
use App\Entities\Article\Article;
use App\Exceptions\CommandException;
use App\Exceptions\HttpException;
use App\Http\Actions\ActionInterface;
use App\Http\ErrorResponse;
use App\Http\Request;
use App\Http\Response;
use App\Http\SuccessfulResponse;
use App\Repositories\ArticleRepository;

class CreateArticle implements ActionInterface
{
    public function __construct(
        private ?ArticleRepository $repository = null,
        private ?CreateArticleCommand $createCommand = null
    )
    {
        $this->repository = $this->repository ?? new ArticleRepository();
        $this->createCommand = $this->createCommand ?? new CreateArticleCommand($this->repository);
    }

    public function handle(Request $request): Response
    {
        try {
            $article = new Article(
                $request->jsonBodyField('authorId'),
                $request->jsonBodyField('title'),
                $request->jsonBodyField('text'),
            );

            $this->createCommand->handle($article);
        } catch (HttpException|CommandException $exception) {
            return new ErrorResponse($exception->getMessage());
        }

        return new SuccessfulResponse([
            'title' => $article->getTitle(),
        ]);
    }
}
