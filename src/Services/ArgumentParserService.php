<?php

namespace App\Services;

use App\Classes\Argument;
use App\Classes\ArgumentInterface;
use App\Exceptions\ArgumentException;
use App\Exceptions\CommandException;

class ArgumentParserService implements ArgumentParserServiceInterface
{
    private ArgumentInterface $argument;

    public function __construct(ArgumentInterface $argument = null)
    {
        $this->argument = $argument ?? new Argument();
    }

    public function parseRawInput(
        iterable $rawInput,
        array $scheme
    ): Argument
    {
        foreach ($rawInput as $argument) {
            $arguments = explode('=', $argument);

            if (count($arguments) !== 2) {
                throw new ArgumentException();
            }

            if(!empty($arguments[0]) && !empty($arguments[1]))
            {
                $this->argument->add($arguments[0], $arguments[1]);
            }
        }

        foreach ($scheme as $argument) {
            if (!array_key_exists($argument, $this->argument->getArguments())) {
                throw new CommandException(
                    sprintf("No required argument provided %s %s ", PHP_EOL, $argument)
                );
            }

            if (empty($this->argument->getArguments()[$argument])) {
                throw new CommandException(
                "Empty argument provided: $argument" .PHP_EOL
                );
            }
        }

        return $this->argument;
    }
}