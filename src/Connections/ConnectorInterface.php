<?php

namespace App\Connections;

use App\Drivers\Connection;

interface ConnectorInterface
{
    public function getConnection(): Connection;
}