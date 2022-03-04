<?php

namespace App\Decorator;

use App\Classes\Argument;

interface DecoratorInterface
{
    public function getFieldData(): Argument;
    public function getRequiredFields();
}