<?php

namespace App\Entities\Comment;

use App\Entities\Article\Article;
use App\Entities\User\User;

class Comment implements CommentInterface
{
    public function __construct(
        private int $id,
        private User $author,
        private Article $article,
        private string $text,
    ) {}

    public function getId(): int
    {
        return $this->id;
    }

    public function getAuthor(): User
    {
        return $this->author;
    }

    public function getArticle(): Article
    {
        return $this->article;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function __toString(): string
    {
        return sprintf(
            "[%d] %s %s %s",
            $this->getId(),
            $this->getAuthor(),
            $this->getArticle(),
            $this->getText(),
        );
    }
}