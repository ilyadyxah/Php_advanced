<?php

namespace App\Factories;

use App\Decorator\UserDecorator;
use App\Entities\EntityInterface;
use App\Enums\Argument;
use App\Exceptions\ArgumentException;
use App\Exceptions\CommandException;
use App\Exceptions\MatchException;

class EntityFactory implements EntityFactoryInterface
{
    private ?UserFactoryInterface $userFactory;
    private ?ArticleFactoryInterface $articleFactory;
    private ?CommentFactoryInterface $commentFactory;

    public function __construct(
        UserFactoryInterface $userFactory = null,
        ArticleFactoryInterface $articleFactory = null,
        CommentFactoryInterface $commentFactory = null
    )
    {
        $this->userFactory = $userFactory ?? new UserFactory();
        $this->articleFactory = $articleFactory ?? new ArticleFactory();
        $this->commentFactory = $commentFactory ?? new CommentFactory();;
    }

    /**
     * @throws MatchException
     * @throws CommandException
     * @throws ArgumentException
     */
    public function create(string $entityType, array $arguments): EntityInterface
    {
        return match ($entityType)
        {
            Argument::USER->value => $this->userFactory->create(new UserDecorator($arguments)),
//            Argument::ARTICLE->value => $this->articleFactory->create(new ArticleDecorator($arguments)),
//             Argument::COMMENT->value => $this->articleFactory->create(new CommentDecorator($arguments)),
            default => throw new MatchException(
                sprintf(
                    "Аргумент должен содержать одно из перечисленных значений: '%s'.",
                    implode("', '", array_map(fn (Argument $argument) => $argument->value, Argument::ARGUMENTS))
                )
            )
        };
    }
}