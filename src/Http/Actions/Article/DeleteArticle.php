<?php

namespace App\Http\Actions\Article;

use App\Commands\CreateCommand;
use App\Commands\DeleteCommand;
use App\Entities\Article\Article;
use App\Exceptions\CommandException;
use App\Exceptions\HttpException;
use App\Http\Actions\ActionInterface;
use App\Http\ErrorResponse;
use App\Http\Request;
use App\Http\Response;
use App\Http\SuccessfulResponse;
use App\Repositories\ArticleRepository;

class DeleteArticle implements ActionInterface
{
    public function __construct(
        private ?ArticleRepository $repository = null,
        private ?DeleteCommand $deleteCommand = null
    )
    {
        $this->repository = $this->repository ?? new ArticleRepository();
        $this->deleteCommand = $this->deleteCommand ?? new DeleteCommand($this->repository);
    }

    public function handle(Request $request): Response
    {
        try {
            $article = new Article(
                $request->jsonBodyField('authorId'),
                $request->jsonBodyField('title'),
                $request->jsonBodyField('text'),
            );

            $this->deleteCommand->handle($article);
        } catch (HttpException|CommandException $exception) {
            return new ErrorResponse($exception->getMessage());
        }

        return new SuccessfulResponse([
            'title' => $article->getTitle(),
        ]);
    }
}
