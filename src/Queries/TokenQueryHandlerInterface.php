<?php

namespace App\Queries;

use App\Entities\Token\AuthToken;

interface TokenQueryHandlerInterface
{
    /**
     * @return AuthToken[]
     */
    public function handle(): array;
}