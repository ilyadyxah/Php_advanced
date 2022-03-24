<?php

namespace Add\Services;

use Add\Decorator\Decorator;
use Add\Exceptions\ArgumentException;
use Add\Exceptions\CommandException;

class ReplaceService
{
    protected Decorator $decorator;
    protected string $text;
    protected string $search;
    protected string $replace;

    /**
     * @throws CommandException
     * @throws ArgumentException
     */
    public function __construct($arguments, $text)
    {
        $this->decorator = new Decorator(array_slice($arguments, 1));
        $this->search = $this->decorator->search;
        $this->replace = $this->decorator->replace;
        $this->text = $text;
    }

    public function replace(): string
    {
        $array_text = explode(' ', $this->text);

        return str_replace($this->search, $this->replace, $this->text);
    }
}