<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use App\App;
use App\Config;
use App\Api\AdsApi;
use App\Common\Request\Request;
use App\Common\Response\JsonApiResponse;
use App\Common\Route\Router;
use App\Common\Route\Rout;
use App\Common\Resources\Resources;
use App\Service\DBSqlite;
use App\Repository\AdsRepository;
use App\Repository\Strategy\AdvancedDBRelevantStrategy;
use App\Repository\Strategy\DefaultDBRelevantStrategy;

$configData = include __DIR__ . '/../config/config.php';
$config = new Config($configData);

if ($config->getMode() == 'dev') {
    ini_set('display_errors', 'On');
}

$request = new Request();
$response = new JsonApiResponse();

// Ads
$db = new DBSqlite(['filename' => $config->getDatabse()]);
$adsStrategy = null;
if ($config->getRelevantStrategy() == 'advanced') {
    $adsStrategy = new AdvancedDBRelevantStrategy($db);
} elseif ($config->getRelevantStrategy() == 'default') {
    $adsStrategy = new DefaultDBRelevantStrategy($db);
}
$adsRepository = new AdsRepository($db);
$adsRepository->setRelevantStrategy($adsStrategy);
$adsApi = new AdsApi($request, $response, $adsRepository);

// Регистрация ресурсов
$resources = new Resources();
$resources->reg(AdsApi::class, $adsApi);

// Регистрация роутов
$router = new Router();
$router->reg(new Rout('POST', '/^ads$/', AdsApi::class, 'addAd'));
$router->reg(new Rout('POST', '/^ads\/(?<id>\d+)$/', AdsApi::class, 'editAd'));
$router->reg(new Rout('GET', '/^ads\/relevant$/', AdsApi::class, 'relevantAd'));

// App
$app = new App($request, $response, $resources, $router);
$app->setMode($config->getMode());
$app->run();
