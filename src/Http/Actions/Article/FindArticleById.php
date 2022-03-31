<?php

namespace App\Http\Actions\Article;

use App\Entities\Article\Article;
use App\Exceptions\HttpException;
use App\Exceptions\UserNotFoundException;
use App\Http\Actions\ActionInterface;
use App\Http\ErrorResponse;
use App\Http\Request;
use App\Http\Response;
use App\Http\SuccessfulResponse;
use App\Repositories\ArticleRepository;
use App\Repositories\ArticleRepositoryInterface;

class FindArticleById implements ActionInterface
{
    public function __construct(private ?ArticleRepositoryInterface $repository = null) {
        $this->repository = $this->repository ?? new ArticleRepository();
    }

    public function handle(Request $request): Response
    {
        try {
            $id = $request->query('id');
        } catch (HttpException $e) {
            return new ErrorResponse($e->getMessage());
        }

        try {
            $article = $this->repository->get($id);
        } catch (UserNotFoundException $e) {
            return new ErrorResponse($e->getMessage());
        }

        /** @var Article $article */
        return new SuccessfulResponse([
            'authorId' => $article->getAuthor(),
            'title' => $article->getTitle(),
            'content' =>$article->getText(),
        ]);
    }
}