<?php

namespace App\Commands;

use App\Drivers\Connection;
use App\Entities\Token\AuthToken;
use App\Exceptions\AuthTokensRepositoryException;
use DateTimeInterface;
use PDOException;

class UpdateTokenCommandHandler implements TokenCommandHandlerInterface
{
    public function __construct(private Connection $connection)
    {
    }

    public function handle(AuthToken $authToken): void
    {
        try {
            $this->connection
                ->prepare($this->getSQL())
                ->execute(
                    [
                        ':expires_on' => $authToken->getExpiresOn()
                            ->format(DateTimeInterface::ATOM),
                    ]
                );
        } catch (PDOException $e) {
            throw new AuthTokensRepositoryException(
                $e->getMessage(), (int)$e->getCode(), $e
            );
        }
    }

    public function getSQL(): string
    {
        return "INSERT INTO Token (
               expires_on
           ) VALUES (
               :expires_on
           )
           ON CONFLICT (token) DO UPDATE SET
               expires_on = :expires_on";
    }
}