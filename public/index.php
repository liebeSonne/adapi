<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use App\Config;
use App\AppFactory;

$configData = include __DIR__ . '/../config/config.php';
$config = new Config($configData);

if ($config->getMode() == 'dev') {
    ini_set('display_errors', 'On');
}

$app = AppFactory::creteAdsApp($config);
$app->run();
