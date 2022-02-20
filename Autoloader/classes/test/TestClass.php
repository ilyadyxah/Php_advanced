<?php

class TestClass
{
    protected int $id;

    public function __construct()
    {
        $this->id = 1;
    }

    public function __toString(): string
    {
        return $this->id;
    }
}