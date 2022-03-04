<?php

namespace App\Migrations;

interface Migrations
{
    public function execute():void;
}