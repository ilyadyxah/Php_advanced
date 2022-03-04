<?php

namespace App\Entities\User;

use App\Entities\EntityInterface;

interface UserInterface extends EntityInterface
{
    public function getFirstName(): string;
    public function getLastName(): string;

}