<?php

namespace App\Commands;

use App\Entities\EntityInterface;
use App\Exceptions\CommandException;
use App\Repositories\ArticleRepositoryInterface;

class DeleteArticleCommand implements CommandHandlerInterface
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
        $itemId = $this->articleRepository->getId($entity);
        if (!$itemId) {
            throw new CommandException(sprintf("%s not exists", get_class($entity)));
        }

        $this->articleRepository->delete($itemId);
    }
}