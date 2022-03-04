<?php

namespace App\Decorator;

use App\Classes\Argument;
use App\Exceptions\ArgumentException;
use App\Exceptions\CommandException;
use App\Services\ArgumentParserService;
use App\Services\ArgumentParserServiceInterface;

abstract class Decorator implements DecoratorInterface
{
    protected array $arguments = [];
    private ArgumentParserServiceInterface $argumentParserService;

    public function __construct(array $arguments, ArgumentParserServiceInterface $argumentParserService = null)
    {
        $this->arguments = $arguments;
        $this->argumentParserService = $argumentParserService ?? new ArgumentParserService();
    }

    /**
     * @throws CommandException
     * @throws ArgumentException
     */
    public function getFieldData(): Argument
    {
        $this->argumentParserService = $argumentParserService ?? new ArgumentParserService();
        return $this->argumentParserService->parseRawInput($this->arguments, $this->getRequiredFields());
    }

    abstract public function getRequiredFields():array;
}