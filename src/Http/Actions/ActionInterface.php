<?php

namespace App\Http\Actions;

use App\Http\Request;
use App\Http\Response;

interface ActionInterface
{
    public function handle(Request $request): Response;
}