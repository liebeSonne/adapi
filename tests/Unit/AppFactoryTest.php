<?php

namespace Tests\Unit;

use App\AppFactory;
use App\Config;
use App\App;
use PHPUnit\Framework\TestCase;

class AppFactoryTest extends TestCase
{
    /**
     * Проверка формирования экземплята приложения.
     *
     */
    public function testCreteAdsApp()
    {
        $config = new Config();
        $app = AppFactory::creteAdsApp($config);

        $this->assertInstanceOf(App::class, $app);
    }

    /**
     * Проверка формирования экземплята приложения.
     * С стратегией по default.
     *
     */
    public function testCreteAdsAppStrategyDefault()
    {
        $configData = [
            'relevant' => [
                'strategy' => 'default',
            ]
        ];
        $config = new Config($configData);
        $app = AppFactory::creteAdsApp($config);

        $this->assertInstanceOf(App::class, $app);
    }


    /**
     * Проверка формирования экземплята приложения.
     * С стратегией по advance.
     *
     */
    public function testCreteAdsAppStrategyAdvanced()
    {
        $configData = [
            'relevant' => [
                'strategy' => 'advanced',
            ]
        ];
        $config = new Config($configData);
        $app = AppFactory::creteAdsApp($config);

        $this->assertInstanceOf(App::class, $app);
    }
}
