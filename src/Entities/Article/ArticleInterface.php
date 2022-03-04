<?php

namespace App\Entities\Article;

use App\Entities\EntityInterface;
use App\Entities\User\User;

interface ArticleInterface extends EntityInterface
{
    public function getAuthor(): User;
    public function getTitle(): string;
    public function getText(): string;
}