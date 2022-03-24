<?php

namespace Add\Services;

use Add\Classes\Argument;

interface ArgumentParserServiceInterface
{
    /**
     * @return Argument
     */
    public function parseRawInput(
        array $rawInput,
        array $scheme
    ): Argument;
}