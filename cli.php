<?php

use App\Commands\CreateCommand;
use App\Commands\DeleteCommand;
use App\Entities\User\User;
use App\Enums\Argument;
use App\Exceptions\NotFoundException;
use App\Factories\EntityManagerFactory;
use App\Factories\EntityManagerFactoryInterface;

require_once __DIR__ . '/vendor/autoload.php';

$argv = ['cli.php', 'user', 'firstName=name1', 'lastName=name2', 'email=mail2ru'];
//$argv = ['cli.php', 'article', 'authorId=2', 'title=someTitle', 'text=sometext'];
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
    $entityManger = EntityManagerFactory::getInstance();

    $commandCreate = new CreateCommand($entityManger->getRepositoryByInputArguments($argv));
    $commandCreate->handle($entityManger->createEntityByInputArguments($argv));


//    $commandDelete = new DeleteCommand($entityManger->getRepositoryByInputArguments($argv));
//    $commandDelete->handle($entityManger->createEntityByInputArguments($argv));

    //про это расскажу на следущей лекции, это тоже паттерн команда, основная мысль,
    // заворачивать запросы или простые операции в отдельные объекты. то есть в команде у нас выполняется какое-нибудь действией с базой,
    // которое мы можем вызывать в любой момент, например мне нужно создать 3 миллиона юзеров на портале я создаю очередь которая будет каждую итерацию
    // создавать юзера, команды часто используются кроном(программа которая запускается в указанное ей время).
} catch (Exception $exception) {
    echo $exception->getMessage() . PHP_EOL;
    http_response_code(404);
}