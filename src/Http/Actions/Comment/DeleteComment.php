<?php

namespace App\Http\Actions\Comment;

use App\Commands\DeleteCommand;
use App\Entities\Comment\Comment;
use App\Exceptions\CommandException;
use App\Exceptions\HttpException;
use App\Http\Actions\ActionInterface;
use App\Http\ErrorResponse;
use App\Http\Request;
use App\Http\Response;
use App\Http\SuccessfulResponse;
use App\Repositories\CommentRepository;

class DeleteComment implements ActionInterface
{
    public function __construct(
        private ?CommentRepository $repository = null,
        private ?DeleteCommand     $deleteCommand = null
    )
    {
        $this->repository = $this->repository ?? new CommentRepository();
        $this->deleteCommand = $this->deleteCommand ?? new DeleteCommand($this->repository);
    }

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
            return new ErrorResponse($exception->getMessage());
        }

        return new SuccessfulResponse([
            'text' => $comment->getText(),
        ]);
    }
}
