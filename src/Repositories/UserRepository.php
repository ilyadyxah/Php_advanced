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
            ->prepare("INSERT INTO users (first_name, last_name, email, password) 
                VALUES (:first_name, :last_name, :email, :password)");

        $statement->execute(
            [
                ':first_name' => $entity->getFirstName(),
                ':last_name' => $entity->getLastName(),
                ':email' => $entity->getEmail(),
                ':password' => $entity->setPassword($entity->getPassword())
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

    public function getUserByEmail(string $email): User
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM users WHERE email = :email'
        );

        $statement->execute([
            ':email' => $email,
        ]);

        return $this->getUser($statement);
    }


    /**
     * @throws UserNotFoundException
     */
    public function findById(int $id): User
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM users WHERE id = :id'
        );

        $statement->execute([
            ':id' => (string)$id,
        ]);

        return $this->getUser($statement);
    }

    /**
     * @throws UserNotFoundException
     */
    private function getUser(PDOStatement $statement): User
    {
        $userData = $statement->fetch(PDO::FETCH_OBJ);

        if (!$userData) {
            throw new UserNotFoundException('User not found');
        }

        $user =
            new User(
                $userData->first_name,
                $userData->last_name,
                $userData->email,
                $userData->password
            );

        $user->setId($userData->id);

        return $user;
    }
}