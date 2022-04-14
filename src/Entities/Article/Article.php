<?php

namespace App\Entities\Article;

class Article implements ArticleInterface
{

    private ?int $id = null;

    public function __construct(
        private int $authorId,
        private string $title,
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

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function __toString(): string
    {
        return sprintf(
            "[%d] %s %s %s",
            $this->getId(),
            $this->getAuthor(),
            $this->getTitle(),
            $this->getText(),
        );
    }
}