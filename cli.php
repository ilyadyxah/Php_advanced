<?php

use App\Commands\CommandHandlerInterface;
use App\Commands\CreateArticleCommand;
use App\Commands\CreateCommentCommand;
use App\Commands\CreateUserCommand;
use App\Commands\DeleteArticleCommand;
use App\Commands\DeleteCommentCommand;
use App\Commands\DeleteUserCommand;
use App\Container\DIContainer;
use App\Entities\Article\Article;
use App\Entities\Comment\Comment;
use App\Entities\User\User;
use App\Enums\Argument;
use App\Exceptions\NotFoundException;
use App\Factories\EntityManagerFactory;
use App\Factories\EntityManagerFactoryInterface;

require_once __DIR__ . '/vendor/autoload.php';

//$argv = ['cli.php', 'user', 'firstName=name1', 'lastName=name2', 'email=mail2ru'];
$argv = ['cli.php', 'article', 'authorId=2', 'title=someTitle', 'text=sometext'];
//$argv = ['cli.php', 'comment', 'articleId=2', 'authorId=2', 'text=sometext2'];

try {
    if (count($argv) < 2) {
        throw new NotFoundException('404');
    }


    if (!in_array($argv[1], Argument::getArgumentValues())) {
        throw new NotFoundException('404');
    }
    /**
     * @var EntityManagerFactoryInterface $entityManger
     */
    $entityMangerFactory = new EntityManagerFactory();
    $entity =  $entityMangerFactory->createEntityByInputArguments($argv);

    /**
     * @var DIContainer $container
     */
    if(isset($container)) {
        /**
         * @var CommandHandlerInterface $commandHandler
         */
        $commandHandler =  match ($entity::class)
        {
            Article::class => $container->get(CreateArticleCommand::class),
            Comment::class => $container->get(CreateCommentCommand::class),
            User::class => $container->get(CreateUserCommand::class)
        };
        $commandHandler->handle($entity);
    }
//    if(isset($container)) {
//        /**
//         * @var CommandHandlerInterface $commandHandler
//         */
//        $commandHandler =  match ($entity::class)
//        {
//            Article::class => $container->get(DeleteArticleCommand::class),
//            Comment::class => $container->get(DeleteCommentCommand::class),
//            User::class => $container->get(DeleteUserCommand::class)
//        };
//        $commandHandler->handle($entity);
//    }

} catch (Exception $exception) {
    echo $exception->getMessage() . PHP_EOL;
    http_response_code(404);
}