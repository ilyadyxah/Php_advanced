<?php

namespace App\Commands;

use App\Entities\EntityInterface;
use App\Exceptions\CommandException;
use App\Repositories\CommentRepositoryInterface;

class DeleteCommentCommand implements CommandHandlerInterface
{
    private CommentRepositoryInterface $commentRepository;

    public function __construct(CommentRepositoryInterface $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    /**
     * @throws CommandException
     */
    public function handle(EntityInterface $entity): void
    {
        $itemId = $this->commentRepository->getId($entity);
        if (!$itemId) {
            throw new CommandException(sprintf("%s not exists", get_class($entity)));
        }

        $this->commentRepository->delete($itemId);
    }
}