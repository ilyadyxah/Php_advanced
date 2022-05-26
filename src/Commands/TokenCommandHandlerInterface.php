<?php

namespace App\Commands;

use App\Entities\Token\AuthToken;

interface TokenCommandHandlerInterface extends CommandInterface
{
    public function handle(AuthToken $authToken): void;
}