<?php

namespace App\Commands;

use App\Entities\EntityInterface;
use App\Exceptions\CommandException;
use App\Repositories\ArticleRepositoryInterface;

class CreateArticleCommand implements CommandHandlerInterface
{
    private ArticleRepositoryInterface $articleRepository;

    public function __construct(ArticleRepositoryInterface $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    /**
     * @throws CommandException
     */
    public function handle(EntityInterface $entity): void
    {
        if ($this->articleRepository->getId($entity)) {
            throw new CommandException(sprintf("%s already exists", get_class($entity)));
        }

        $this->articleRepository->save($entity);
    }
}