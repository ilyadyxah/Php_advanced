<?php

namespace Tests\Traits;

use Psr\Log\LoggerInterface;
use Tests\Dummy\DummyLogger;

trait LoggerTrait
{
    use ContainerTrait;

    private function getLogger():LoggerInterface
    {
        $container = $this->getContainer()->bind(LoggerInterface::class, new DummyLogger());
        return $container->get(LoggerInterface::class);
    }
}