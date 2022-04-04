<?php

namespace Tests\Container;

use App\Config\SqlLiteConfig;
use App\Drivers\Connection;
use App\Drivers\PdoConnectionDriver;
use App\Exceptions\NotFoundException;
use App\Repositories\UserRepository;
use App\Repositories\UserRepositoryInterface;
use PHPUnit\Framework\TestCase;
use Tests\Traits\LoggerTrait;

class DIContainerTest extends TestCase
{
    use LoggerTrait;

    public function testItThrowsAnExceptionIfCannotResolveType(): void
    {
        $container = $this->getContainer();

        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage(
            'Cannot resolve type: Tests\Container\SomeClass'
        );

        $container->get(SomeClass::class);
    }

    public function testItResolvesClassWithoutDependencies(): void
    {
        $container = $this->getContainer();

        $object = $container->get(SomeClassWithoutDependencies::class);

        $this->assertInstanceOf(
            SomeClassWithoutDependencies::class,
            $object
        );
    }

    public function testItResolvesClassByContract(): void
    {
        $container = $this->getContainer();

        $container->bind(
            UserRepositoryInterface::class,
            UserRepository::class
        );
        $this->bindConnection();

        $object = $container->get(UserRepositoryInterface::class);

        $this->assertInstanceOf(
            UserRepository::class,
            $object
        );
    }

    public function testItReturnsPredefinedObject(): void
    {
        $container = $this->getContainer();

        $container->bind(
            SomeClassWithParameter::class,
            new SomeClassWithParameter(42)
        );
        $this->bindConnection();

        $object = $container->get(SomeClassWithParameter::class);

        $this->assertInstanceOf(
            SomeClassWithParameter::class,
            $object
        );

        $this->assertSame(42, $object->value());
    }


    public function testItResolvesClassWithDependencies(): void
    {
        $container = $this->getContainer();

        $container->bind(
            SomeClassWithParameter::class,
            new SomeClassWithParameter(10000)
        );
        $this->bindConnection();

        $object = $container->get(ClassDependingOnAnother::class);

        $this->assertInstanceOf(
            ClassDependingOnAnother::class,
            $object
        );
    }

    private function bindConnection(): void
    {
        $container = $this->getContainer();
        $container->bind(
            Connection::class,
            PdoConnectionDriver::getInstance(SqlLiteConfig::DSN)
        );
    }
}