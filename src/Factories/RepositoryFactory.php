<?php

namespace App\Factories;

use App\Connections\ConnectorInterface;
use App\Connections\SqlLiteConnector;
use App\Entities\EntityInterface;
use App\Entities\User\User;
use App\Repositories\EntityRepositoryInterface;
use App\Repositories\UserRepository;
use JetBrains\PhpStorm\Pure;

class RepositoryFactory implements RepositoryFactoryInterface
{
    private ConnectorInterface $connector;

    #[Pure] public function __construct(ConnectorInterface $connector = null)
    {
        $this->connector = $connector ?? new SqlLiteConnector();
    }

    #[Pure] public function create(EntityInterface $entity): EntityRepositoryInterface
    {
        return match ($entity::class)
        {
            User::class => new UserRepository($this->connector),
            //Article::class => $this->createArticleRepository(),
        };
    }

}