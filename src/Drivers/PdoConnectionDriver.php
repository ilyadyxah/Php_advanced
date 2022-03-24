<?php

namespace App\Drivers;

class PdoConnectionDriver extends \PDO implements Connection{

    protected static array $instances = [];

    private function __construct($dsn, $userName = null, $password = null, $options = null)
    {
        parent::__construct($dsn, $userName, $password, $options);
    }

    public static function getInstance(
        string $dsn,
        ?string $userName = null,
        ?string $password = null,
        ?array $options = null
    ): self
    {
        $class = static::class;
        if (!isset(self::$instances[$class])) {
            self::$instances[$class] = new static(
                    $dsn,
                    $userName,
                    $password,
                    $options
                );
        }

        return self::$instances[$class];
    }

    public function executeQuery(string $query, array $params):void
    {
        $this->prepare($query)->execute($params);
    }
}