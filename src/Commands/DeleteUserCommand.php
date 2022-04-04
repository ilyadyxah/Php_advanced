<?php

namespace App\Commands;

use App\Entities\EntityInterface;
use App\Exceptions\CommandException;
use App\Repositories\UserRepositoryInterface;

class DeleteUserCommand implements CommandHandlerInterface
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
        $itemId = $this->userRepository->getId($entity);
        if (!$itemId) {
            throw new CommandException(sprintf("%s not exists", get_class($entity)));
        }

        $this->userRepository->delete($itemId);
    }
}