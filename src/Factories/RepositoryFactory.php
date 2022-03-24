<?php

namespace App\Factories;

use App\Config\ProjectConfig;
use App\Connections\ConnectorInterface;
use App\Connections\MySqlConnector;
use App\Connections\SqlLiteConnector;
use App\Entities\Article\Article;
use App\Entities\Comment\Comment;
use App\Entities\EntityInterface;
use App\Entities\User\User;
use App\Exceptions\NotFoundDatabaseException;
use App\Repositories\ArticleRepository;
use App\Repositories\EntityRepositoryInterface;
use App\Repositories\CommentRepository;
use App\Repositories\UserRepository;
use JetBrains\PhpStorm\Pure;

class RepositoryFactory implements RepositoryFactoryInterface
{
    private ConnectorInterface $connector;

    /**
     * @throws NotFoundDatabaseException
     */
    public function __construct(ConnectorInterface $connector = null)
    {
        $this->connector = $connector ?? $this->getConnector();
    }

    #[Pure] public function create(EntityInterface $entity): EntityRepositoryInterface
    {
        return match ($entity::class)
        {
            User::class => new UserRepository($this->connector),
            Article::class => new ArticleRepository($this->connector),
            Comment::class => new CommentRepository($this->connector),
        };
    }

    /**
     * @throws NotFoundDatabaseException
     */
    public function getConnector(): ConnectorInterface
    {
        return match (ProjectConfig::DATABASE) {
            'mysql' => new MySqlConnector,
            'sqlite' => new SqlLiteConnector,
            default => throw new NotFoundDatabaseException(
                sprintf("Cannot find database in config, current database: %s", ProjectConfig::DATABASE)),
        };
    }

}