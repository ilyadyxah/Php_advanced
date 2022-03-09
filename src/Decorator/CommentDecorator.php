<?php

namespace App\Decorator;

use App\Exceptions\ArgumentException;
use App\Exceptions\CommandException;
use App\Services\ArgumentParserServiceInterface;

class CommentDecorator extends Decorator implements DecoratorInterface
{
    public const ID = 'id';
    public const ARTICLE_ID = 'articleId';
    public const AUTHOR_ID = 'authorId';
    public const TEXT = 'text';

    public ?int $id = null;
    public int $articleId;
    public int $authorId;
    public string $text;

    public const REQUIRED_FIELDS = [
        self::ARTICLE_ID,
        self::AUTHOR_ID,
        self::TEXT
    ];

    private ?ArgumentParserServiceInterface $argumentParserService;

    /**
     * @throws ArgumentException
     * @throws CommandException
     */
    public function __construct(array $arguments)
    {
        parent::__construct($arguments);
        $userFieldData = $this->getFieldData();

        $this->id = $userFieldData->get(self::ID) ?? null;
        $this->articleId = $userFieldData->get(self::ARTICLE_ID) ?? null;
        $this->authorId = $userFieldData->get( self::AUTHOR_ID) ?? null;
        $this->text = $userFieldData->get( self::TEXT) ?? null;
    }

    public function getRequiredFields(): array
    {
        return static::REQUIRED_FIELDS;
    }
}