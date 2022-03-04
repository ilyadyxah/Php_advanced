<?php

namespace App\Repositories;

use App\Entities\User\User;
use App\Exceptions\UserNotFoundException;

class MemoryUserRepository implements MemoryUserRepositoryInterface
{
    protected array $users;

    public function save(User $user)
    {
        $this->users[$user->getId()] = $user;
    }

    public function get(int $id):User
    {
        if(!in_array($id, array_keys($this->users)))
        {
            throw new UserNotFoundException('User not found');
        }

        return $this->users[$id];
    }
}


