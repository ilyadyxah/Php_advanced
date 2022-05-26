<?php

namespace App\Http\Actions\Like;

use App\Commands\AddLikeCommand;
use App\Entities\Like\Like;
use App\Exceptions\AuthException;
use App\Exceptions\CommandException;
use App\Exceptions\HttpException;
use App\Http\Actions\ActionInterface;
use App\Http\Auth\PasswordAuthenticationInterface;
use App\Http\ErrorResponse;
use App\Http\Request;
use App\Http\Response;
use App\Http\SuccessfulResponse;
use Psr\Log\LoggerInterface;

class AddLike implements ActionInterface
{
    public function __construct(
        private LoggerInterface $logger,
        private PasswordAuthenticationInterface $passwordAuthentication,
        private AddLikeCommand  $addLikeCommand
    )
    {
    }

    public function handle(Request $request): Response
    {
        try {
            $author = $this->passwordAuthentication->getUser($request);
            $this->logger->info("Пользователь аутентифицирован");
        } catch (HttpException|AuthException $e) {
            return new ErrorResponse($e->getMessage());
        }
        try {
            $like = new Like(
                $request->jsonBodyField('articleId'),
                $request->jsonBodyField('userId'),
                $request->jsonBodyField('like'),
            );
        } catch (HttpException $e) {
            $this->logger->error($e->getMessage());
            return new ErrorResponse($e->getMessage());
        }

        try {
            $this->addLikeCommand->handle($like);
        } catch (CommandException $e) {
            return new ErrorResponse($e->getMessage());
        }

        $this->logger->info('Like created');

        return new SuccessfulResponse([
            'articleId' => $like->getArticleId(),
            'userId' => $like->getUserId(),
        ]);
    }
}