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
        $statement =  $this->connection
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

    public function delete($id):void
    {
        /**
         * @var User $entity
         */
        $statement =  $this->connection
            ->prepare("DELETE FROM users WHERE `id` = :id");

        $statement->execute(
            [
                ':id' => $id,
            ]
        );
    }

    public function getId($entity): string|int
    {
        $statement = $this->connection->prepare(
            'SELECT id FROM users WHERE email = :email'
        );

        $statement->execute([
            ':email' => (string)$entity->getEmail(),
        ]);

        return $statement->fetch(PDO::FETCH_ASSOC)['id'] ?? false;
    }


    /**
     * @throws UserNotFoundException
     */
    public function get(int $id): User
    {
        $statement = $this->connection->prepare(
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