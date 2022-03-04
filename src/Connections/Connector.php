<?php

namespace App\Connections;
use PDO;

abstract class Connector implements ConnectorInterface
{
    public function getConnection():PDO
    {
        return new PDO($this->getDsn(), $this->getUserName(), $this->getPassword(), $this->getOptions());
    }

    abstract public function getDsn():string;
    abstract public function getUserName():string;
    abstract public function getPassword():string;
    abstract public function getOptions():array;
}