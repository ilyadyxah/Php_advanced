<?php

namespace App\Http\Auth;

use App\Entities\User\User;
use App\Http\Request;

interface IdentificationInterface
{
    public function getUser(Request $request): User;
}