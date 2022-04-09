<?php

namespace App\Queries;

use App\Drivers\Connection;
use App\Entities\Token\AuthToken;
use App\Exceptions\AuthTokensRepositoryException;
use App\Repositories\UserRepositoryInterface;
use DateTimeImmutable;
use Exception;
use PDO;
use PDOException;

class TokenQueryHandler implements TokenQueryHandlerInterface
{
    public function __construct(
        private Connection $connection,
        private UserRepositoryInterface $userRepository
    ){}

    /**
     * @return AuthToken[]
     * @throws AuthTokensRepositoryException
     */
    public function handle(): array
    {
        try {
            $statement = $this->connection->prepare($this->getSQL());
            $statement->execute();

            $tokensData = $statement->fetchAll(PDO::FETCH_OBJ);


            try {
                $tokens = [];

                foreach ($tokensData as $tokenData)
                {
                    $tokens[$tokenData->token] = new AuthToken(
                        $tokenData->token,
                        $this->userRepository->findById($tokenData->user_id),
                        new DateTimeImmutable($tokenData->expires_on)
                    );
                }


                return $tokens;

            } catch (Exception $e) {
                throw new AuthTokensRepositoryException(
                    $e->getMessage()
                );
            }

        }catch (PDOException $e)
        {
            throw new AuthTokensRepositoryException($e->getMessage());
        }
    }

    public function getSQL(): string
    {
        return "SELECT * FROM tokens";
    }
}