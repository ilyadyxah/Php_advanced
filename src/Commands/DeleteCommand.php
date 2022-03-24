<?php

namespace App\Commands;

use App\Entities\EntityInterface;
use App\Exceptions\CommandException;
use App\Repositories\EntityRepositoryInterface;

class DeleteCommand
{
    private EntityRepositoryInterface $entityRepository;

    public function __construct(EntityRepositoryInterface $entityRepository)
    {
        $this->entityRepository = $entityRepository;
    }

    /**
     * @throws CommandException
     */
    public function handle(EntityInterface $entity): void
    {
        $itemId = $this->entityRepository->getId($entity);
        if (!$itemId) {
            throw new CommandException(sprintf("%s not exists", get_class($entity)));
        }

        $this->entityRepository->delete($itemId);
    }
}