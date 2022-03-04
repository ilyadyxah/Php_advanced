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

    public function handle(EntityInterface $entity): void
    {
        if ($entity->getId()) {
            throw new CommandException(sprintf("%s already exists", $entity::class));
        }

        $this->entityRepository->save($entity);
    }
}