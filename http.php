<?php

use App\Http\Actions\Article\CreateArticle;
use App\Http\Actions\Article\DeleteArticle;
use App\Http\Actions\Article\FindArticleById;
use App\Http\Actions\Comment\CreateComment;
use App\Http\Actions\Comment\DeleteComment;
use App\Http\Actions\Comment\FindCommentById;
use App\Http\Actions\Like\AddLike;
use App\Http\ErrorResponse;
use App\Http\Request;
use Psr\Log\LoggerInterface;

$container = require __DIR__ . '/bootstrap.php';
/**
 * @var LoggerInterface $logger
 */
$logger = $container->get(LoggerInterface::class);

$request = new Request(
    $_GET,
    $_SERVER,
    file_get_contents('php://input'),
);

try {
    $path = $request->path();
} catch (HttpException $e) {
    $logger->warning($e->getMessage());
    (new ErrorResponse)->send();
    return;
}

try {
    $method = $request->method();
} catch (HttpException $e) {
    $logger->warning($e->getMessage());
    (new ErrorResponse)->send();
    return;
}

$routes = [
    'GET' => [
        '/article/show' => FindArticleById::class,
        '/comment/show' => FindCommentById::class,

    ],
    'POST' => [
        '/article/create' => CreateArticle::class,
        '/article/delete' => DeleteArticle::class,
        '/comment/create' => CreateComment::class,
        '/comment/delete' => DeleteComment::class,
        '/like/add' => AddLike::class,
    ],
];

if (!array_key_exists($method, $routes)) {
    $logger->info(sprintf('Клиент с IP-адресом :%s пытался получить несуществующий роут', $_SERVER['REMOTE_ADDR']));
    (new ErrorResponse('Not found'))->send();
    return;
}

if (!array_key_exists($path, $routes[$method])) {
    $logger->info(sprintf('Клиент с IP-адресом :%s пытался получить несуществующий роут', $_SERVER['REMOTE_ADDR']));
    (new ErrorResponse('Not found'))->send();
    return;
}

$actionClassName = $routes[$method][$path];
$action = $container->get($actionClassName);

try {
    $response = $action->handle($request);
} catch (Exception $e) {
    $logger->error($e->getMessage());
    (new ErrorResponse($e->getMessage()))->send();
}

$response->send();
