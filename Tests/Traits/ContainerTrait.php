<?php

namespace Tests\Traits;

use App\Container\DIContainer;
use Psr\Container\ContainerInterface;

trait ContainerTrait
{
    private function getContainer():ContainerInterface
    {
        return DIContainer::getInstance();
    }
}