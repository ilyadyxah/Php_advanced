<?php

namespace App\Enums;

enum AuthToken: string
{
    case HEADER_PREFIX = 'Bearer ';
}