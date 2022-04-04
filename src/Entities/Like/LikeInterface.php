<?php

namespace App\Entities\Like;

use App\Entities\EntityInterface;

interface LikeInterface extends EntityInterface
{

    public function getArticleId(): int;
    public function getUserId(): int;
}