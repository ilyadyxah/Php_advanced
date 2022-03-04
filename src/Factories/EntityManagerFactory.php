<?php

namespace App\Factories;

use App\Entities\EntityInterface;
use App\Exceptions\ArgumentException;
use App\Exceptions\CommandException;
use App\Exceptions\MatchException;
use App\Repositories\EntityRepositoryInterface;

class EntityManagerFactory extends Factory implements EntityManagerFactoryInterface
{
    private ?EntityFactoryInterface $entityFactory;
    private ?RepositoryFactoryInterface $repositoryFactory;

    protected function __construct(
        EntityFactoryInterface $entityFactory = null,
        RepositoryFactoryInterface $repositoryFactory = null
    )
    {
        $this->entityFactory = $entityFactory ?? new EntityFactory();
        $this->repositoryFactory = $repositoryFactory ?? new RepositoryFactory();
    }

    /**
     * @throws ArgumentException
     * @throws CommandException
     * @throws MatchException
     */
    public function createEntity(string $entityType, array $arguments): EntityInterface
    {
        return $this->entityFactory->create($entityType, $arguments);
    }

    public function getRepository(EntityInterface $entity): EntityRepositoryInterface
    {
        return $this->repositoryFactory->create($entity);
    }

    /**
     * @throws ArgumentException
     * @throws CommandException
     * @throws MatchException
     */
    public function createEntityByInputArguments(array $arguments): EntityInterface
    {
        return $this->createEntity($arguments[1], array_slice($arguments, 2));
    }

    /**
     * @throws ArgumentException
     * @throws CommandException
     * @throws MatchException
     */
    public function getRepositoryByInputArguments(array $arguments): EntityRepositoryInterface
    {
        return $this->getRepository($this->createEntityByInputArguments($arguments));
    }
}