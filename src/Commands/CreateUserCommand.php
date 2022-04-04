<?php

namespace App\Commands;

use App\Entities\EntityInterface;
use App\Exceptions\CommandException;
use App\Repositories\UserRepositoryInterface;

class CreateUserCommand implements CommandHandlerInterface
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @throws CommandException
     */
    public function handle(EntityInterface $entity): void
    {
        if ($this->userRepository->getId($entity)) {
            throw new CommandException(sprintf("%s already exists", get_class($entity)));
        }

        $this->userRepository->save($entity);
    }
}