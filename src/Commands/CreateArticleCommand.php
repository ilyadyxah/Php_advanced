<?php

namespace App\Commands;

use App\Entities\Article\Article;
use App\Entities\EntityInterface;
use App\Exceptions\CommandException;
use App\Repositories\ArticleRepositoryInterface;
use Psr\Log\LoggerInterface;

class CreateArticleCommand implements CommandHandlerInterface
{
    public function __construct(
        private ArticleRepositoryInterface $articleRepository,
        private LoggerInterface            $logger
    ){}

    /**
     * @throws CommandException
     * @var Article $entity
     */
    public function handle(EntityInterface $entity): void
    {
        $this->logger->info('Create article command started');
        $articleId = $this->articleRepository->getId($entity);

        if ($articleId) {
            $this->logger->warning('Article already exists, id: ' . $articleId);
            throw new CommandException(sprintf("%s already exists", get_class($entity)));
        }

        $this->articleRepository->save($entity);
        $this->logger->info('Article created, title: ' . $entity->getTitle());
    }
}