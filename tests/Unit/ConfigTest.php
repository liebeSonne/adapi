<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Config;

class ConfigTest extends TestCase
{
    /**
     * Возвращает массивы данных для создания класса Config.
     *
     * @return array
     */
    public function dataProviderConfigData(): array
    {
        return [
            [
                [
                    'mode' => 'dev',
                    'db' => [
                        'filename' => 'db.filename',
                        'schema' => [
                            's1' => 'schema1',
                        ],
                    ],
                    'relevant' => [
                        'strategy' => 'default',
                    ],
                ]
            ],
            [
                [
                    'mode' => 'prod',
                    'db' => [
                        'filename' => 'db1',
                        'schema' => [],
                    ],
                    'relevant' => [
                        'strategy' => 'advanced',
                    ],
                ]
            ],
        ];
    }

    /**
     * Проверка конструктора.
     *
     * @dataProvider dataProviderConfigData
     */
    public function testConstruct($data)
    {
        $config = new Config($data);

        $this->assertIsString($config->getMode());
        $this->assertIsString($config->getDatabse());
        $this->assertIsArray($config->getDatabaseSchemas());
        $this->assertIsString($config->getRelevantStrategy());

        $this->assertEquals($data['mode'], $config->getMode());
        $this->assertEquals($data['db']['filename'], $config->getDatabse());
        $this->assertEquals($data['db']['schema'], $config->getDatabaseSchemas());
        $this->assertEquals($data['relevant']['strategy'], $config->getRelevantStrategy());
    }

    /**
     * Проверка конструктора без данных.
     *
     */
    public function testConstructEmpty()
    {
        $config = new Config();

        $this->assertIsString($config->getMode());
        $this->assertIsString($config->getDatabse());
        $this->assertIsArray($config->getDatabaseSchemas());
        $this->assertIsString($config->getRelevantStrategy());

        $this->assertEquals('', $config->getMode());
        $this->assertEquals('', $config->getDatabse());
        $this->assertEquals([], $config->getDatabaseSchemas());
        $this->assertEquals('', $config->getRelevantStrategy());
    }
}
