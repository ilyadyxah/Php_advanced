<?php

use App\Migrations\Migration_version_1;

require_once __DIR__ . '/vendor/autoload.php';

$migration = new Migration_version_1();
$migration->execute();