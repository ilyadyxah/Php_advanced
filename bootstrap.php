<?php

use App\Commands\TokenCommandHandler;
use App\Commands\TokenCommandHandlerInterface;
use App\Commands\UpdateTokenCommandHandler;
use App\Commands\UpdateTokenCommandHandlerInterface;
use App\Container\DIContainer;
use App\Drivers\Connection;
use App\Drivers\PdoConnectionDriver;
use App\Http\Auth\BearerTokenAuthentication;
use App\Http\Auth\IdentificationInterface;
use App\Http\Auth\JsonBodyUserEmailIdentification;
use App\Http\Auth\PasswordAuthentication;
use App\Http\Auth\PasswordAuthenticationInterface;
use App\Http\Auth\TokenAuthenticationInterface;
use App\Queries\TokenQueryHandler;
use App\Queries\TokenQueryHandlerInterface;
use App\Repositories\ArticleRepository;
use App\Repositories\ArticleRepositoryInterface;
use App\Repositories\AuthTokensRepository;
use App\Repositories\AuthTokensRepositoryInterface;
use App\Repositories\CommentRepository;
use App\Repositories\CommentRepositoryInterface;
use App\Repositories\LikeRepository;
use App\Repositories\LikeRepositoryInterface;
use App\Repositories\UserRepository;
use App\Repositories\UserRepositoryInterface;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

require_once __DIR__ . '/vendor/autoload.php';
Dotenv\Dotenv::createImmutable(__DIR__)->safeLoad();

$container = DIContainer::getInstance();

$container->bind(
    UserRepositoryInterface::class,
    UserRepository::class
);

$container->bind(
    ArticleRepositoryInterface::class,
    ArticleRepository::class
);

$container->bind(
    CommentRepositoryInterface::class,
    CommentRepository::class
);

$container->bind(
    CommentRepositoryInterface::class,
    CommentRepository::class
);

$container->bind(
    LikeRepositoryInterface::class,
    LikeRepository::class
);

$container->bind(
    Connection::class,
    PdoConnectionDriver::getInstance($_SERVER['DSN_DATABASE'])
);

$container->bind(
    AuthTokensRepositoryInterface::class,
    AuthTokensRepository::class
);

$container->bind(
    IdentificationInterface::class,
    JsonBodyUserEmailIdentification::class
);

$container->bind(
    PasswordAuthenticationInterface::class,
    PasswordAuthentication::class
);

$container->bind(
    TokenAuthenticationInterface::class,
    BearerTokenAuthentication::class
);

$container->bind(
    TokenQueryHandlerInterface::class,
    TokenQueryHandler::class
);

$container->bind(
    TokenCommandHandlerInterface::class,
    TokenCommandHandler::class
);

$container->bind(
    UpdateTokenCommandHandlerInterface::class,
    UpdateTokenCommandHandler::class
);

$logger = new Logger('geekbrains');

$isNeedLogToFile = (bool)$_SERVER['LOG_TO_FILES'];
$isNeedLogToConsole = (bool)$_SERVER['LOG_TO_CONSOLE'];

if($isNeedLogToFile)
{
    $logger
        ->pushHandler(new StreamHandler(
            __DIR__ . '/.logs/geekbrains.log'
        ))
        ->pushHandler(new StreamHandler(
            __DIR__ . '/.logs/geekbrains.error.log',
            level: Logger::ERROR,
            bubble: false,
        ));
}

if($isNeedLogToConsole)
{
    $logger->pushHandler(new StreamHandler("php://stdout"));
}

$container->bind(
    LoggerInterface::class,
    (new Logger('geekbrains'))
        ->pushHandler(
            new StreamHandler(
                __DIR__ . '/.logs/geekbrains.log'
            )
        )
        ->pushHandler(
            new StreamHandler(
                __DIR__ . '/.logs/geekbrains.error.log',
                level: Logger::ERROR,
                bubble: false,
            )
        )
        ->pushHandler(new StreamHandler("php://stdout"))
);

return $container;
