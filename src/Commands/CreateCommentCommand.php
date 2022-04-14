<?php

namespace App\Commands;

use App\Entities\Comment\Comment;
use App\Entities\EntityInterface;
use App\Exceptions\CommandException;
use App\Repositories\CommentRepositoryInterface;
use Psr\Log\LoggerInterface;

class CreateCommentCommand implements CommandHandlerInterface
{
    public function __construct(
        private CommentRepositoryInterface $commentRepository,
        private LoggerInterface            $logger
    ) {}

    /**
     * @throws CommandException
     * @var Comment $entity
     */
    public function handle(EntityInterface $entity): void
    {
        $this->logger->info('Create comment command started');
        $commentId = $this->commentRepository->getId($entity);

        if ($commentId) {
            $this->logger->warning('Comment already exists, id: ' . $commentId);
            throw new CommandException(sprintf("%s already exists", get_class($entity)));
        }

        $this->commentRepository->save($entity);
        $this->logger->info(sprintf('Comment created, author: %s, article: %s ', $entity->getAuthor(), $entity->getArticle()));
    }
}