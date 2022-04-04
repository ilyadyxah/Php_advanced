<?php

namespace App\Repositories;

use App\Drivers\Connection;
use App\Entities\EntityInterface;
use App\Entities\Article\Article;
use App\Exceptions\ArticleNotFoundException;
use PDO;
use PDOStatement;
use Psr\Log\LoggerInterface;

class ArticleRepository extends EntityRepository implements ArticleRepositoryInterface
{
    private LoggerInterface $logger;

    public function __construct(
        LoggerInterface $logger,
        ?Connection     $connection = null,
    )
    {
        parent::__construct($connection);
        $this->logger = $logger;
    }

    /**
     * @param EntityInterface $entity
     * @return void
     */
    public function save(EntityInterface $entity): void
    {
        /**
         * @var Article $entity
         */
        $statement = $this->connection
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

    public function delete($id): void
    {
        /**
         * @var Article $entity
         */
        $statement = $this->connection
            ->prepare("DELETE FROM articles WHERE `id` = :id");

        $statement->execute(
            [
                ':id' => $id,
            ]
        );
    }

    public function getId($entity): int|null
    {
        /**
         * @var Article $entity
         */
        $statement = $this->connection->prepare(
            'SELECT id FROM articles WHERE author_id = :authorId AND title = :title'
        );


        $what = $statement->execute([
            ':authorId' => (string)$entity->getAuthor(),
            ':title' => (string)$entity->getTitle(),

        ]);
        return $statement->fetch(PDO::FETCH_ASSOC)['id'] ?? false;
    }

    /**
     * @throws ArticleNotFoundException
     */
    public function get(int $id): Article
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM articles WHERE id = :id'
        );

        $statement->execute([
            ':id' => (string)$id,
        ]);

        return $this->getArticle($statement);
    }

    /**
     * @throws ArticleNotFoundException
     */
    private function getArticle(PDOStatement $statement): Article
    {
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        if (false === $result) {
            $this->logger->error('Article not found');
            throw new ArticleNotFoundException("Cannot find article");
        }

        return new Article($result['author_id'], $result['title'], $result['text']);
    }
}