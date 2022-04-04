<?php

namespace App\Commands;

use App\Entities\EntityInterface;
use App\Exceptions\CommandException;
use App\Repositories\LikeRepositoryInterface;
use Psr\Log\LoggerInterface;

class AddLikeCommand implements CommandHandlerInterface
{
    public function __construct(
        private LikeRepositoryInterface $likeRepository,
        private LoggerInterface $logger
    ) {}

    /**
     * @throws CommandException
     */
    public function handle(EntityInterface $entity): void
    {
        $likeId = $this->likeRepository->getLikeId($entity);

        if ($likeId) {
            $this->logger->warning('Like already exists, id' . $likeId);
            throw new CommandException( 'Like already exists');
        }

        if (!$this->likeRepository->isArticle($entity)) {
            $this->logger->warning('Article not found');
            throw new CommandException("Article not exists");
        }
        if (!$this->likeRepository->isUser($entity)) {
            $this->logger->warning('User not found');
            throw new CommandException("User not exists");
        }

        $this->likeRepository->save($entity);
    }
}