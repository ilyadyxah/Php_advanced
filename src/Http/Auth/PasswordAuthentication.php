<?php

namespace App\Http\Auth;

use App\Entities\User\User;
use App\Exceptions\AuthException;
use App\Exceptions\HttpException;
use App\Http\Request;
use App\Repositories\UserRepositoryInterface;

class PasswordAuthentication implements PasswordAuthenticationInterface
{
    public function __construct(private UserRepositoryInterface $usersRepository) {}

    public function getUser(Request $request): User
    {
        try {
            $email = $request->jsonBodyField('email');
        } catch (HttpException $e) {
            throw new AuthException($e->getMessage());
        }

        try {
            $user =  $this->usersRepository->getUserByEmail($email);
        } catch (\Exception $e) {
            throw new AuthException($e->getMessage());
        }

        try {
            $password = $request->jsonBodyField('password');
        } catch (HttpException $e) {
            throw new AuthException($e->getMessage());
        }

        if (!$user->checkPassword($password)) {
            throw new AuthException('Wrong password');
        }

        return $user;
    }
}