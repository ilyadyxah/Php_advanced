<?php

namespace App\Repositories;

use App\Entities\EntityInterface;

interface ArticleRepositoryInterface extends EntityRepositoryInterface
{
    public function getId(EntityInterface $entity);
}