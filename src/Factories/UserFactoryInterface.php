<?php

namespace App\Factories;

use App\Decorator\UserDecorator;
use App\Entities\User\UserInterface;

interface UserFactoryInterface extends FactoryInterface
{
    public function create(UserDecorator $userDecorator): UserInterface;
}