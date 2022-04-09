<?php

namespace App\Repositories;

use App\Connections\ConnectorInterface;
use App\Connections\SqlLiteConnector;
use App\Drivers\Connection;
use App\Entities\EntityInterface;

abstract class EntityRepository implements EntityRepositoryInterface
{
    public function __construct(protected ?Connection $connection = null)
    {
        $this->connection = $this->connection ?? (new SqlLiteConnector)->getConnection();
    }

    abstract public function save(EntityInterface $entity):void;
    abstract public function findById(int $id): EntityInterface;
}