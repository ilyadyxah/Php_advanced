<?php

namespace Add\Decorator;

use Add\Classes\Argument;

interface DecoratorInterface
{
    public function getFieldData(): Argument;
    public function getRequiredFields();
}