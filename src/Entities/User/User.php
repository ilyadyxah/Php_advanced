<?php

namespace App\Entities\User;

class User implements UserInterface
{
    private ?int $id = null;

    public function __construct(
        private string $firstName,
        private string $lastName,
        private string $email
    ) {}

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function __toString(): string
    {
        return sprintf(
            "[%d] %s %s %s",
            $this->getId(),
            $this->getFirstName(),
            $this->getLastName(),
            $this->getEmail()
        );
    }
}