<?php

namespace App\Entities\Like;

class Like implements LikeInterface
{
    private ?int $id = null;

    public function __construct(
        private int $articleId,
        private int $userId,
        private bool $like,
    ) {}

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getArticleId(): int
    {
        return $this->articleId;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function isLike(): bool
    {
        return $this->like;
    }

    public function __toString(): string
    {
        return sprintf(
            "[%d] %s %s",
            $this->getId(),
            $this->getArticleId(),
            $this->getUserId(),
        );
    }
}
