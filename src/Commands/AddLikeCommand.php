<?php

namespace App\Commands;

use App\Entities\EntityInterface;
use App\Exceptions\CommandException;
use App\Repositories\LikeRepositoryInterface;

class AddLikeCommand implements CommandHandlerInterface
{
    private LikeRepositoryInterface $likeRepository;

    public function __construct(LikeRepositoryInterface $likeRepository)
    {
        $this->likeRepository = $likeRepository;
    }

    /**
     * @throws CommandException
     */
    public function handle(EntityInterface $entity): void
    {
        if ($this->likeRepository->getLikeId($entity)) {
            throw new CommandException( 'Like already exists');
        }
        if (!$this->likeRepository->isArticle($entity)) {
            throw new CommandException("Article not exists");
        }
        if (!$this->likeRepository->isUser($entity)) {
            throw new CommandException("User not exists");
        }

        $this->likeRepository->save($entity);
    }
}