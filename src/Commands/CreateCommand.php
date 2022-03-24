<?php

namespace App\Commands;

use App\Entities\EntityInterface;
use App\Exceptions\CommandException;
use App\Repositories\EntityRepositoryInterface;

class CreateCommand
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
        if ($this->entityRepository->getId($entity)) {
            throw new CommandException(sprintf("%s already exists", get_class($entity)));
        }

        $this->entityRepository->save($entity);
    }
}