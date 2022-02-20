<?php

namespace Admin\project;

use Faker\Factory;

class Comment
{
    protected $id;
    protected $authorId;
    protected $newspaperId;
    protected $content;

    public function __construct()
    {
        $this->id = Factory::create()->randomDigit();
        $this->authorId = Factory::create()->randomDigit();
        $this->newspaperId = Factory::create()->randomDigit();
        $this->content = Factory::create()->text(100);
    }

    public function __toString(): string
    {
        return $this->content;
    }
}