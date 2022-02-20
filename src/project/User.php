<?php

namespace Admin\project;

use Faker\Factory;

class User
{
    protected int $id;
    protected $firstName;
    protected $lastName;

    public function __construct()
    {
        $this->id = Factory::create()->randomDigit();
        $this->firstName = Factory::create()->firstName;
        $this->lastName = Factory::create()->lastName;
    }

    public function __toString(): string
    {
        return $this->firstName . " " . $this->lastName;
    }
}