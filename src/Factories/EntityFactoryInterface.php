<?php

namespace App\Factories;

use App\Entities\EntityInterface;

interface EntityFactoryInterface
{
    public function create(string $entityType, array $arguments): EntityInterface;
}