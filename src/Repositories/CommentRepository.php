<?php

namespace App\Repositories;

use App\Entities\Comment\Comment;
use App\Entities\EntityInterface;
use App\Entities\User\User;
use App\Exceptions\CommentNotFoundException;
use App\Exceptions\UserNotFoundException;
use PDO;
use PDOStatement;

class CommentRepository extends EntityRepository implements CommentRepositoryInterface
{
    /**
     * @param EntityInterface $entity
     * @return void
     */
    public function save(EntityInterface $entity):void
    {
        /**
         * @var Comment $entity
         */
        $statement =  $this->connection
            ->prepare("INSERT INTO comments (article_id, author_id, text) 
                VALUES (:article_id, :author_id, :text)");

        $statement->execute(
            [
                ':article_id' => $entity->getArticle(),
                ':author_id' => $entity->getAuthor(),
                ':text' => $entity->getText(),
            ]
        );
    }

    public function delete($id):void
    {
        /**
         * @var Comment $entity
         */
        $statement =  $this->connection
            ->prepare("DELETE FROM comments WHERE `id` = :id");

        $statement->execute(
            [
                ':id' => $id,
            ]
        );
    }

    public function getId($entity): string|int
    {
        /**
         * @var Comment $entity
         */
        $statement = $this->connection->prepare(
            'SELECT id FROM comments WHERE author_id = :authorId AND article_id = :articleId'
        );

        $statement->execute([
            ':authorId' => (string)$entity->getAuthor(),
            ':articleId' => (string)$entity->getArticle(),

        ]);

        return $statement->fetch(PDO::FETCH_ASSOC)['id'] ?? false;
    }

    /**
     * @throws CommentNotFoundException
     */
    public function get(int $id): Comment
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM comments WHERE id = :id'
        );

        $statement->execute([
            ':id' => (string)$id,
        ]);

        return $this->getComment($statement);
    }

    /**
     * @throws CommentNotFoundException
     */
    private function getComment(PDOStatement $statement): Comment
    {
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        if (false === $result) {
            throw new CommentNotFoundException("Cannot find comment");
        }

        return new Comment($result['article_id'], $result['author_id'], $result['text']);
    }
}