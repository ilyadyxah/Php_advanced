<?php

namespace App\Repositories;

use App\Entities\EntityInterface;
use App\Entities\User\User;
use App\Exceptions\UserNotFoundException;
use PDO;
use PDOStatement;

class UserRepository extends EntityRepository implements UserRepositoryInterface
{
    /**
     * @param EntityInterface $entity
     * @return void
     */
    public function save(EntityInterface $entity):void
    {
        /**
         * @var User $entity
         */
        $statement =  $this->connector->getConnection()
            ->prepare("INSERT INTO users (first_name, last_name, email) 
                VALUES (:first_name, :last_name, :email)");

        $statement->execute(
            [
                ':first_name' => $entity->getFirstName(),
                ':last_name' => $entity->getLastName(),
                ':email' => $entity->getEmail(),
            ]
        );
    }

    /**
     * @throws UserNotFoundException
     */
    public function get(int $id): User
    {
        $statement = $this->connector->getConnection()->prepare(
            'SELECT * FROM users WHERE id = :id'
        );

        $statement->execute([
            ':id' => (string)$id,
        ]);

        return $this->getUser($statement, $id);
    }

    /**
     * @throws UserNotFoundException
     */
    private function getUser(PDOStatement $statement, int $userId): User
    {
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        if (false === $result) {
            throw new UserNotFoundException(
                sprintf("Cannot find user with id: %s", $userId));
        }

        return new User($result['first_name'], $result['last_name'], $result['email']);
    }
}