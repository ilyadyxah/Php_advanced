<?php

namespace Tests\Container;

class SomeClassWithParameter
{
    public function __construct(private int $value) {}

    public function value(): int
    {
        return $this->value;
    }
}