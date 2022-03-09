<?php

namespace App\Repositories;

use App\Entities\EntityInterface;
use App\Entities\Article\Article;
use App\Exceptions\UserNotFoundException;
use PDO;
use PDOStatement;

class ArticleRepository extends EntityRepository implements ArticleRepositoryInterface
{
    /**
     * @param EntityInterface $entity
     * @return void
     */
    public function save(EntityInterface $entity):void
    {
        /**
         * @var Article $entity
         */
        $statement =  $this->connector->getConnection()
            ->prepare("INSERT INTO articles (author_id, title, text) 
                VALUES (:author_id, :title, :text)");

        $statement->execute(
            [
                ':author_id' => $entity->getAuthor(),
                ':title' => $entity->getTitle(),
                ':text' => $entity->getText(),
            ]
        );
    }

    /**
     * @throws UserNotFoundException
     */
    public function get(int $id): Article
    {
        $statement = $this->connector->getConnection()->prepare(
            'SELECT * FROM articles WHERE id = :id'
        );

        $statement->execute([
            ':id' => (string)$id,
        ]);

        return $this->getArticle($statement, $id);
    }

    /**
     * @throws UserNotFoundException
     */
    private function getArticle(PDOStatement $statement, int $articleId): Article
    {
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        if (false === $result) {
            throw new UserNotFoundException(
                sprintf("Cannot find user with id: %s", $articleId));
        }

        return new Article($result['author_id'], $result['title'], $result['text']);
    }
}