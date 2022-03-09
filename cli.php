<?php

use App\Commands\CreateCommand;
use App\Enums\Argument;
use App\Exceptions\NotFoundException;
use App\Factories\EntityManagerFactory;
use App\Factories\EntityManagerFactoryInterface;

require_once 'vendor/autoload.php';

//$argv = ['cli.php', 'user', 'firstName=name1', 'lastName=name2', 'email=mailru'];
//$argv = ['cli.php', 'article', 'authorId=1', 'title=someTitle', 'text=sometext'];
$argv = ['cli.php', 'comment', 'articleId=1', 'authorId=3', 'text=sometext'];

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

    $command = new CreateCommand($entityManger->getRepositoryByInputArguments($argv));
    $command->handle($entityManger->createEntityByInputArguments($argv));
    //про это расскажу на следущей лекции, это тоже паттерн команда, основная мысль,
    // заворачивать запросы или простые операции в отдельные объекты. то есть в команде у нас выполняется какое-нибудь действией с базой,
    // которое мы можем вызывать в любой момент, например мне нужно создать 3 миллиона юзеров на портале я создаю очередь которая будет каждую итерацию
    // создавать юзера, команды часто используются кроном(программа которая запускается в указанное ей время).
} catch (Exception $exception) {
    echo $exception->getMessage() . PHP_EOL;
    http_response_code(404);
}