<?php

namespace App\Http\Actions\Comment;

use App\Entities\Article\Article;
use App\Entities\Comment\Comment;
use App\Exceptions\HttpException;
use App\Exceptions\UserNotFoundException;
use App\Http\Actions\ActionInterface;
use App\Http\ErrorResponse;
use App\Http\Request;
use App\Http\Response;
use App\Http\SuccessfulResponse;
use App\Repositories\CommentRepository;
use App\Repositories\CommentRepositoryInterface;

class FindCommentById implements ActionInterface
{
    public function __construct(private ?CommentRepositoryInterface $repository = null) {
        $this->repository = $this->repository ?? new CommentRepository();
    }

    public function handle(Request $request): Response
    {
        try {
            $id = $request->query('id');
        } catch (HttpException $e) {
            return new ErrorResponse($e->getMessage());
        }

        try {
            $comment = $this->repository->get($id);
        } catch (UserNotFoundException $e) {
            return new ErrorResponse($e->getMessage());
        }

        /** @var Comment $comment */
        return new SuccessfulResponse([
            'authorId' => $comment->getAuthor(),
            'title' => $comment->getArticle(),
            'content' =>$comment->getText(),
        ]);
    }
}