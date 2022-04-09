<?php

namespace App\Entities\Token;

use App\Entities\User\User;
use DateTimeImmutable;
use DateTimeInterface;

class AuthToken implements AuthTokenInterface
{
    public function __construct(
        private string $token,
        private User $user,
        private DateTimeImmutable $expiresOn
    ) {}

    public function getToken(): string
    {
        return $this->token;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getExpiresOn(): DateTimeImmutable
    {
        return $this->expiresOn;
    }

    public function isExpires(): bool
    {
        return new \DateTimeImmutable() > $this->getExpiresOn();
    }

    public function setExpiresToNow(): void
    {
        $this->expiresOn = new DateTimeImmutable();
    }

    public function __toString(): string
    {
        return sprintf(
            "[%d] %s %s %s",
            $this->getToken(),
            $this->getToken(),
            $this->getUser()->getId(),
            $this->getExpiresOnString()
        );
    }

    public function __serialize(): array
    {
       return
       [
          'token' => $this->getToken(),
          'userId' => $this->getUser()->getId(),
          'expiresOn' => $this->getExpiresOnString()
       ];
    }

    private function getExpiresOnString():string
    {
        return $this->getExpiresOn()->format(DateTimeInterface::ATOM);
    }
}