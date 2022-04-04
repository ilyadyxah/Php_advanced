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
use App\Repositories\ArticleRepositoryInterface;
use Psr\Log\LoggerInterface;

class FindArticleById implements ActionInterface
{
    public function __construct(
        private ArticleRepositoryInterface $repository,
        private LoggerInterface $logger
    ) {}

    public function handle(Request $request): Response
    {
        try {
            $id = $request->query('id');
        } catch (HttpException $e) {
            $this->logger->error($e->getMessage());
            return new ErrorResponse($e->getMessage());
        }
        try {
            $article = $this->repository->get($id);
        } catch (UserNotFoundException $e) {
            $this->logger->warning($e->getMessage());
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