<?php

use App\Http\Actions\Article\CreateArticle;
use App\Http\Actions\Article\DeleteArticle;
use App\Http\Actions\Article\FindArticleById;
use App\Http\Actions\Comment\CreateComment;
use App\Http\Actions\Comment\DeleteComment;
use App\Http\Actions\Comment\FindCommentById;
use App\Http\ErrorResponse;
use App\Http\Request;

require_once __DIR__ . '/vendor/autoload.php';

$request = new Request(
    $_GET,
    $_SERVER,
    file_get_contents('php://input'),
);

try {
    $path = $request->path();
} catch (HttpException) {
    (new ErrorResponse)->send();
    return;
}

try {
    $method = $request->method();
} catch (HttpException) {
    (new ErrorResponse)->send();
    return;
}

$routes = [
    'GET' => [
        '/article/show' => new FindArticleById(),
        '/comment/show' => new FindCommentById(),

    ],
    'POST' => [
        '/article/create' => new CreateArticle(),
        '/article/delete' => new DeleteArticle(),
        '/comment/create' => new CreateComment(),
        '/comment/delete' => new DeleteComment(),
    ],
];

if (!array_key_exists($method, $routes)) {
    (new ErrorResponse('Not found'))->send();
    return;
}

if (!array_key_exists($path, $routes[$method])) {
    (new ErrorResponse('Not found'))->send();
    return;
}

// Выбираем действие по методу и пути
$action = $routes[$method][$path];

try {
    $response = $action->handle($request);
} catch (Exception $e) {
    (new ErrorResponse($e->getMessage()))->send();
}

$response->send();
