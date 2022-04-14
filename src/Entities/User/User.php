<?php

namespace App\Entities\User;

class User implements UserInterface
{
    private ?int $id = null;

    public function __construct(
        private string $firstName,
        private string $lastName,
        private string $email,
        private string $password,
    ) {}

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
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

    public function setPassword(string $password):string
    {
        $this->password = self::hash($password, $this->getEmail());

        return $this->password;
    }

    public function checkPassword(string $password): bool
    {
        return $this->password === self::hash($password, $this->getEmail());
    }

    private static function hash(string $password, string $email): string
    {
        return hash('sha256', $email.$password);
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }
}