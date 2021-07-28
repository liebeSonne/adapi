<?php

declare(strict_types=1);

namespace App;

use App\Common\Request\Request;
use App\Common\Response\JsonApiResponse;
use App\Service\DBSqlite;
use App\Repository\Strategy\DefaultDBRelevantStrategy;
use App\Repository\Strategy\AdvancedDBRelevantStrategy;
use App\Repository\AdsRepository;
use App\Api\AdsApi;
use App\Common\Resources\Resources;
use App\Common\Route\Router;
use App\Common\Route\Rout;

class AppFactory
{
    /**
     * Создание экземпляра приложения для API рекламных объявлений.
     *
     * @param Config $config
     * @return App
     */
    public static function creteAdsApp(Config $config): App
    {
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

        return $app;
    }
}
