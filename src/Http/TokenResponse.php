<?php

namespace App\Http;

use App\Entities\Token\AuthToken;

class TokenResponse extends Response
{
    public function __construct(private AuthToken $authToken){}

    public function send(): void
    {
        $data = ['success' => static::SUCCESS] + $this->payload();
        header('Content-Type: application/json');

        echo serialize($data);
    }

    protected function payload(): array
    {
        return [$this->authToken];
    }
}