<?php

namespace App\Repositories;


use App\Entities\Token\AuthToken;
use App\Entities\User\User;

interface AuthTokensRepositoryInterface
{
    public function getToken(string $token):?AuthToken;
    public function getTokenByUser(User $user):?AuthToken;
}