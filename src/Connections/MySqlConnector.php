<?php

namespace App\Connections;

use App\Config\MySqlConfig;

class MySqlConnector extends Connector implements MySqlConnectorInterface {
    public function getDsn(): string
    {
        return MySqlConfig::DSN;
    }

    public function getUserName(): string
    {
        return MySqlConfig::USERNAME;
    }

    public function getPassword(): string
    {
        return MySqlConfig::PASSWORD;
    }

    public function getOptions(): array
    {
        return [];
    }
};