<?php

namespace App\Entities\Comment;

use App\Entities\Article\Article;
use App\Entities\User\User;

class Comment implements CommentInterface
{
    private ?int $id = null;

    public function __construct(
        private int $articleId,
        private int $authorId,
        private string $text,
    ) {}

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAuthor(): int
    {
        return $this->authorId;
    }

    public function getArticle(): int
    {
        return $this->articleId;
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