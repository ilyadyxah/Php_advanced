<?php

namespace App\Decorator;

use App\Exceptions\ArgumentException;
use App\Exceptions\CommandException;
use App\Services\ArgumentParserServiceInterface;

class ArticleDecorator extends Decorator implements DecoratorInterface
{
    public const ID = 'id';
    public const AUTHOR_ID = 'authorId';
    public const TITLE = 'title';
    public const TEXT = 'text';

    public ?int $id = null;
    public int $authorId;
    public string $title;
    public string $text;

    public const REQUIRED_FIELDS = [
        self::AUTHOR_ID,
        self::TITLE,
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
        $this->authorId = $userFieldData->get(self::AUTHOR_ID) ?? null;
        $this->title = $userFieldData->get( self::TITLE) ?? null;
        $this->text = $userFieldData->get( self::TEXT) ?? null;
    }

    public function getRequiredFields(): array
    {
        return static::REQUIRED_FIELDS;
    }
}