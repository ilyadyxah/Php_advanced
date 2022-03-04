<?php

namespace App\Connections;

use PDO;

interface ConnectorInterface
{
    public function getConnection(): PDO;
}