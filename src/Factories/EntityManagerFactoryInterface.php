<?php

namespace App\Factories;

use App\Entities\EntityInterface;
use App\Repositories\EntityRepositoryInterface;

interface EntityManagerFactoryInterface
{
    public function createEntity(string $entityType, array $arguments): EntityInterface;
    public function getRepository(EntityInterface $entity): EntityRepositoryInterface;
    public function createEntityByInputArguments(array $arguments): EntityInterface;
    public function getRepositoryByInputArguments(array $arguments): EntityRepositoryInterface;

}