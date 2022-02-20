<?php

namespace Admin\project;

use Faker\Factory;

class Newspaper
{
    protected $id;
    protected $authorId;
    protected $title;
    protected $description;

    public function __construct()
    {
        $this->id = Factory::create()->randomDigit();
        $this->authorId = Factory::create()->randomDigit();
        $this->title = Factory::create()->text(20);
        $this->description = Factory::create()->text(100);
    }

    public function __toString(): string
    {
        return $this->title . PHP_EOL . $this->description;
    }
}