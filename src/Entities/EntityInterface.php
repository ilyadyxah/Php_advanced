<?php

namespace App\Entities;

interface EntityInterface
{
    public function getId(): ?int;
    public function __toString(): string;
}