<?php

namespace App\Repositories;

use App\Entities\EntityInterface;

interface EntityRepositoryInterface
{
    public function save(EntityInterface $entity);
    public function get(int $id): EntityInterface;
}