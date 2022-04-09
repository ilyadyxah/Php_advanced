<?php

namespace App\Http\Auth;

use App\Entities\User\User;
use App\Enums\AuthToken;
use App\Exceptions\AuthException;
use App\Exceptions\AuthTokenNotFoundException;
use App\Exceptions\HttpException;
use App\Http\Request;
use App\Repositories\AuthTokensRepositoryInterface;

class BearerTokenAuthentication implements TokenAuthenticationInterface
{
    public function __construct(private AuthTokensRepositoryInterface $authTokensRepository,) {}

    public function getUser(Request $request): User
    {
        try {
            $header = $request->header('Authorization');
        } catch (HttpException $e) {
            throw new AuthException($e->getMessage());
        }

        if (!str_starts_with($header, AuthToken::HEADER_PREFIX->value)) {
            throw new AuthException("Malformed token: [$header]");
        }

        $token = mb_substr($header, strlen(AuthToken::HEADER_PREFIX->value));

        try {
            $authToken = $this->authTokensRepository->getToken($token);
            var_dump($authToken);
            die();
        } catch (AuthTokenNotFoundException) {
            throw new AuthException("Bad token: [$token]");
        }

        if ($authToken->isExpires()) {
            throw new AuthException("Token expired: [$token]");
        }

        return $authToken->getUser();
    }
}