<?php

namespace App\Exceptions;

class UserEmailExistException extends \Exception
{
    protected $message = 'Пользователь с таким email уже существует в системе';
}