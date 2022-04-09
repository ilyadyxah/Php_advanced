<?php

namespace App\Http\Auth;

use App\Entities\User\User;
use App\Http\Request;

interface AuthenticationInterface
{
    public function getUser(Request $request): User;
}