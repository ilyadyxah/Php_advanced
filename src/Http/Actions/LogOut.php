<?php

namespace App\Http\Actions;

use App\Commands\UpdateTokenCommandHandler;
use App\Exceptions\AuthException;
use App\Http\Auth\BearerTokenAuthentication;
use App\Http\ErrorResponse;
use App\Http\Request;
use App\Http\Response;
use App\Http\TokenResponse;
use App\Repositories\AuthTokensRepositoryInterface;

class LogOut implements ActionInterface
{
    public function __construct(
        private BearerTokenAuthentication     $bearerTokenAuthentication,
        private UpdateTokenCommandHandler     $tokenCommandHandler,
        private AuthTokensRepositoryInterface $authTokensRepository
    ) {}

    /**
     * @throws \Exception
     */
    public function handle(Request $request): Response
    {
        try {
            $user = $this->bearerTokenAuthentication->getUser($request);
        } catch (AuthException $e) {
            return new ErrorResponse($e->getMessage());
        }

        $currentToken = $this->authTokensRepository->setExpiresToNow($user);

        $this->tokenCommandHandler->handle($currentToken);
        return new TokenResponse($currentToken);
    }
}