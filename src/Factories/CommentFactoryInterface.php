<?php

namespace App\Factories;

use App\Decorator\CommentDecorator;
use App\Entities\Comment\CommentInterface;

interface CommentFactoryInterface extends FactoryInterface
{
    public function create(CommentDecorator $commentDecorator): CommentInterface;
}