<?php

namespace App\Http\Actions\Article;

use App\Commands\CreateArticleCommand;
use App\Entities\Article\Article;
use App\Exceptions\AuthException;
use App\Exceptions\CommandException;
use App\Exceptions\HttpException;
use App\Http\Actions\ActionInterface;
use App\Http\Auth\PasswordAuthentication;
use App\Http\Auth\PasswordAuthenticationInterface;
use App\Http\ErrorResponse;
use App\Http\Request;
use App\Http\Response;
use App\Http\SuccessfulResponse;
use Psr\Log\LoggerInterface;

class CreateArticle implements ActionInterface
{
    public function __construct(
        private CreateArticleCommand $createCommand,
        private PasswordAuthenticationInterface $passwordAuthentication,
        private LoggerInterface $logger
    ){}

    public function handle(Request $request): Response
    {
        try {
            $author = $this->passwordAuthentication->getUser($request);
            $this->logger->info("Пользователь аутентифицирован");
        } catch (HttpException|AuthException $e) {
            return new ErrorResponse($e->getMessage());
        }

        try {
            $article = new Article(
                $request->jsonBodyField('authorId'),
                $request->jsonBodyField('title'),
                $request->jsonBodyField('text'),
            );

            $this->createCommand->handle($article);
        } catch (HttpException|CommandException $e) {
            $this->logger->warning($e->getMessage());
            return new ErrorResponse($e->getMessage());
        }

        return new SuccessfulResponse([
            'title' => $article->getTitle(),
        ]);
    }
}
