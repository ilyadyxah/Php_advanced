<?php

namespace App\Migrations;

use App\Connections\ConnectorInterface;
use App\Connections\SqlLiteConnector;
use JetBrains\PhpStorm\Pure;

class Migration_version_1 implements Migrations
{
    private ConnectorInterface $connector;

    #[Pure] public function __construct(ConnectorInterface $connector = null)
    {
        $this->connector = $connector ?? new SqlLiteConnector();
    }

    public function execute():void
    {
        $this->connector->getConnection()->query(
            "create table users
                        (
                            id integer
                            constraint users_pk primary key autoincrement,
                            first_name varchar,
                            last_name  varchar,
                            email      varchar
                        );            
                        create unique index users_email_uindex on users (email);
                     "
        );
    }
}