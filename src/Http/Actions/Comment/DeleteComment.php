<?php

namespace App\Http\Actions\Comment;

use App\Commands\DeleteCommentCommand;
use App\Entities\Comment\Comment;
use App\Exceptions\CommandException;
use App\Exceptions\HttpException;
use App\Http\Actions\ActionInterface;
use App\Http\ErrorResponse;
use App\Http\Request;
use App\Http\Response;
use App\Http\SuccessfulResponse;
use Psr\Log\LoggerInterface;

class DeleteComment implements ActionInterface
{
    public function __construct(
        private DeleteCommentCommand $deleteCommand,
        private LoggerInterface $logger
    ) {}

    public function handle(Request $request): Response
    {
        try {
            $comment = new Comment(
                $request->jsonBodyField('authorId'),
                $request->jsonBodyField('articleId'),
                $request->jsonBodyField('text'),
            );

            $this->deleteCommand->handle($comment);
        } catch (HttpException|CommandException $exception) {
            $this->logger->warning($e->getMessage());
            return new ErrorResponse($exception->getMessage());
        }

        return new SuccessfulResponse([
            'text' => $comment->getText(),
        ]);
    }
}
