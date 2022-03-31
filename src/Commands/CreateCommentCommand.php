<?php

namespace App\Commands;

use App\Entities\EntityInterface;
use App\Exceptions\CommandException;
use App\Repositories\CommentRepositoryInterface;

class CreateCommentCommand implements CommandHandlerInterface
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
        if ($this->commentRepository->getId($entity)) {
            throw new CommandException(sprintf("%s already exists", get_class($entity)));
        }

        $this->commentRepository->save($entity);
    }
}