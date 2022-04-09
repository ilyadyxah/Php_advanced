<?php

namespace App\Repositories;

use App\Entities\EntityInterface;
use App\Entities\Like\Like;
use PDO;

class LikeRepository extends EntityRepository implements LikeRepositoryInterface
{
    /**
     * @param EntityInterface $entity
     * @return void
     */
    public function save(EntityInterface $entity): void
    {
        /**
         * @var Like $entity
         */
        $statement = $this->connection
            ->prepare("INSERT INTO likes (article_id, user_id, like) 
                VALUES (:article_id, :user_id, :like)");

        $statement->execute(
            [
                ':article_id' => $entity->getArticleId(),
                ':user_id' => $entity->getUserId(),
                ':like' => true,
            ]
        );
    }

    public function getLikeId($entity): int
    {
        /**
         * @var Like $entity
         */
        $statement = $this->connection->prepare(
            'SELECT id FROM likes WHERE article_id = :articleId AND user_id = :userId AND `like` = :like'
        );

        $statement->execute([
            ':articleId' => (int)$entity->getArticleId(),
            ':userId' => (int)$entity->getUserId(),
            ':like' => true

        ]);

        return $statement->fetch(PDO::FETCH_ASSOC)['id'] ?? false;
    }

    public function isUser($entity): bool
    {
        /**
         * @var Like $entity
         */
        $statement = $this->connection->prepare(
            'SELECT id FROM users WHERE id = :id'
        );

        $statement->execute([
            ':id' => (int)$entity->getUserId(),
        ]);

        return $statement->fetch(PDO::FETCH_ASSOC)['id'] ?? false;
    }

    public function isArticle($entity): bool
    {
        /**
         * @var Like $entity
         */
        $statement = $this->connection->prepare(
            'SELECT id FROM articles WHERE id = :id'
        );

        $statement->execute([
            ':id' => (int)$entity->getArticleId(),
        ]);

        return $statement->fetch(PDO::FETCH_ASSOC)['id'] ?? false;
    }

    public function findById(int $id): EntityInterface
    {
        // TODO: Implement get() method.
    }
}