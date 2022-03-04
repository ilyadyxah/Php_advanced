<?php

namespace App\Factories;

use App\Decorator\UserDecorator;
use App\Entities\User\User;
use App\Entities\User\UserInterface;
use JetBrains\PhpStorm\Pure;

final class UserFactory implements UserFactoryInterface
{
    #[Pure] public function create(UserDecorator $userDecorator): UserInterface
    {
        return new User(
            $userDecorator->firstName,
            $userDecorator->lastName,
            $userDecorator->email,
        );
    }
}