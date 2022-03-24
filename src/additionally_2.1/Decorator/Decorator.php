<?php

namespace Add\Decorator;

use Add\Classes\Argument;
use Add\Exceptions\ArgumentException;
use Add\Exceptions\CommandException;
use Add\Services\ArgumentParserService;
use Add\Services\ArgumentParserServiceInterface;

class Decorator implements DecoratorInterface
{
    public const SEARCH = 'search';
    public const REPLACE = 'replace';

    public string $search;
    public string $replace;

    public const REQUIRED_FIELDS = [
        self::SEARCH,
        self::REPLACE,
    ];

    protected array $arguments = [];
    private ArgumentParserServiceInterface $argumentParserService;

    /**
     * @throws CommandException
     * @throws ArgumentException
     */
    public function __construct(array $arguments, ArgumentParserServiceInterface $argumentParserService = null)
    {
        $this->arguments = $arguments;
        $this->argumentParserService = $argumentParserService ?? new ArgumentParserService();
        $userFieldData = $this->getFieldData();

        $this->search = $userFieldData->get(self::SEARCH) ?? null;
        $this->replace = $userFieldData->get(self::REPLACE) ?? null;
    }

    /**
     * @throws CommandException
     * @throws ArgumentException
     */
    public function getFieldData(): Argument
    {
        return $this->argumentParserService->parseRawInput($this->arguments, $this->getRequiredFields());
    }

    public function getRequiredFields(): array
    {
        return static::REQUIRED_FIELDS;
    }
}