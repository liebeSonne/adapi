<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

ini_set('display_errors', 'On');
echo "[install]\n";

use App\Service\DBSqlite;
use App\Config;

$configData = include __DIR__ . '/../config/config.php';

$config = new Config($configData);
$db = new DBSqlite(['filename' => $config->getDatabse()]);

echo "[init db schema]\n";
foreach ($config->getDatabaseSchemas() as $query) {
    $db->query($query);
}

echo "[ok]\n";
