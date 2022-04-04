<?php

namespace App\Http\Actions\Comment;

use App\Commands\CreateCommentCommand;
use App\Entities\Comment\Comment;
use App\Exceptions\CommandException;
use App\Exceptions\HttpException;
use App\Http\Actions\ActionInterface;
use App\Http\ErrorResponse;
use App\Http\Request;
use App\Http\Response;
use App\Http\SuccessfulResponse;
use Psr\Log\LoggerInterface;

class CreateComment implements ActionInterface
{
    public function __construct(
        private CreateCommentCommand $createCommand,
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

            $this->createCommand->handle($comment);
        } catch (HttpException|CommandException $e) {
            $this->logger->warning($e->getMessage());
            return new ErrorResponse($e->getMessage());
        }

        return new SuccessfulResponse([
            'text' => $comment->getText(),
        ]);
    }
}
