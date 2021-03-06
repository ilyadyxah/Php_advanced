<?php

namespace App\Entities\Comment;

use App\Entities\Article\Article;
use App\Entities\EntityInterface;
use App\Entities\User\User;

interface CommentInterface extends EntityInterface
{
    public function getAuthor(): int;
    public function getArticle(): int;
    public function getText(): string;
}