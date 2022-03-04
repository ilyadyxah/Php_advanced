<?php

namespace App\Services;

use App\Classes\Argument;

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