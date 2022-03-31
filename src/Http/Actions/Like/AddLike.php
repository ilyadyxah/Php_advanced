<?php

namespace App\Http\Actions\Like;

use App\Commands\AddLikeCommand;
use App\Entities\Like\Like;
use App\Exceptions\CommandException;
use App\Exceptions\HttpException;
use App\Http\Actions\ActionInterface;
use App\Http\ErrorResponse;
use App\Http\Request;
use App\Http\Response;
use App\Http\SuccessfulResponse;
use App\Repositories\LikeRepository;
use App\Repositories\LikeRepositoryInterface;

class AddLike implements ActionInterface
{
    public function __construct(
        private ?LikeRepositoryInterface $likeRepository = null,
        private ?AddLikeCommand $addLikeCommand = null
    )
    {
        $this->likeRepository = $this->likeRepository ?? new LikeRepository();
        $this->addLikeCommand = $this->addLikeCommand ?? new AddLikeCommand($this->likeRepository);
    }

    public function handle(Request $request): Response
    {
        {
            try {
                $like = new Like(
                    $request->jsonBodyField('articleId'),
                    $request->jsonBodyField('userId'),
                    $request->jsonBodyField('like'),
                );
            } catch (HttpException $exception) {
                return new ErrorResponse($exception->getMessage());
            }

            try {
                $this->addLikeCommand->handle($like);
            } catch (CommandException $exception) {
                return new ErrorResponse($exception->getMessage());
            }

            return new SuccessfulResponse([
                'articleId' => $like->getArticleId(),
                'userId' => $like->getUserId(),
            ]);
        }
    }
}