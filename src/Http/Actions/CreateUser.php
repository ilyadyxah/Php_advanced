<?php

namespace App\Http\Actions;

use App\Commands\CreateUserCommand;
use App\Commands\EntityCommand;
use App\Entities\User\User;
use App\Exceptions\HttpException;
use App\Exceptions\UserEmailExistException;
use App\Http\ErrorResponse;
use App\Http\Request;
use App\Http\Response;
use App\Http\SuccessfulResponse;


class CreateUser implements ActionInterface
{
    public function __construct(private CreateUserCommand $createUserCommand) {}

    public function handle(Request $request): Response
    {
        try {
            $user = new User(
                $request->jsonBodyField('firstName'),
                $request->jsonBodyField('lastName'),
                $request->jsonBodyField('email'),
                $request->jsonBodyField('password'),
            );

            $this->createUserCommand->handle($user);
        }catch (HttpException|UserEmailExistException $exception)
        {
            return new ErrorResponse($exception->getMessage());
        }

        return new SuccessfulResponse([
            'email' => $user->getEmail(),
        ]);
    }
}