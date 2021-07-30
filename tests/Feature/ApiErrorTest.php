<?php

namespace Tests\Feature;

use Tests\Feature\ApiTestCase;
use Tests\Feature\Helper\DataGenerator;

/**
 * Тесты запросов с валидной ошибкой.
 *
 */
class ApiErrorTest extends ApiTestCase
{
    /**
     * Провайдер данных дляпроверки валидных ошибок на запрос добавления.
     *
     * первый аргумент - массив параметров запроса: text, banner, price, limit
     * второй аргумент - массив параметров ожидаемого ответа: code, message
     *
     * @retunr array
     */
    public function dataProviderErrorValidAddRequest(): array
    {
        return [
            // invalid text
            [
                [
                    'text' => '',
                    'banner' => 'http://banner.com/banner/image.png',
                    'price' => 300,
                    'limit' => 1000,
                ],
                [
                    'code' => 400,
                    'message' => 'Invalid text',
                ]
            ],
            // invalid banner
            [
                [
                    'text' => 'Ad text',
                    'banner' => 'invalid',
                    'price' => 300,
                    'limit' => 1000,
                ],
                [
                    'code' => 400,
                    'message' => 'Invalid banner link',
                ]
            ],
            // invalid price
            [
                [
                    'text' => 'Ad text',
                    'banner' => 'http://banner.com/banner/image.png',
                    'price' => -100,
                    'limit' => 1000,
                ],
                [
                    'code' => 400,
                    'message' => 'Invalid price',
                ]
            ],
            // invalid limit
            [
                [
                    'text' => 'Ad text',
                    'banner' => 'http://banner.com/banner/image.png',
                    'price' => 300,
                    'limit' => -1,
                ],
                [
                    'code' => 400,
                    'message' => 'Invalid limit',
                ]
            ],
        ];
    }

    /**
     * Проверка валидных ошибок на запросы добавления.
     *
     * @dataProvider dataProviderErrorValidAddRequest
     */
    public function testErrorValidOnAddRequest($params, $result)
    {
        $item = $this->helper->prepareAddRequestDataValid($params);
        // проверка запроса
        $res = $this->assertIsRequestResponseErrorValid($item);
        // проверка кода и сообщения
        $this->assertEquals($res['code'], $result['code']);
        $this->assertEquals($res['message'], $result['message']);
    }

    /**
     * Провайдер данных дляпроверки валидных ошибок на запрос добавления.
     *
     * первый аргумент - массив параметров запроса: text, banner, price, limit
     * второй аргумент - массив параметров ожидаемого ответа: code, message
     *
     * @retunr array
     */
    public function dataProviderErrorValidEditRequest(): array
    {
        return [
            // invalid text
            [
                [
                    'text' => '',
                    'banner' => 'http://banner.com/banner/image.png',
                    'price' => 300,
                    'limit' => 1000,
                ],
                [
                    'code' => 400,
                    'message' => 'Invalid text',
                ]
            ],
            // invalid banner
            [
                [
                    'text' => 'Ad text',
                    'banner' => 'invalid',
                    'price' => 300,
                    'limit' => 1000,
                ],
                [
                    'code' => 400,
                    'message' => 'Invalid banner link',
                ]
            ],
            // invalid price
            [
                [
                    'text' => 'Ad text',
                    'banner' => 'http://banner.com/banner/image.png',
                    'price' => -100,
                    'limit' => 1000,
                ],
                [
                    'code' => 400,
                    'message' => 'Invalid price',
                ]
            ],
            // invalid limit
            [
                [
                    'text' => 'Ad text',
                    'banner' => 'http://banner.com/banner/image.png',
                    'price' => 300,
                    'limit' => -1,
                ],
                [
                    'code' => 400,
                    'message' => 'Invalid limit',
                ]
            ],
        ];
    }

    /**
     * Добавление записи для редактирования.
     * Чтоб наверняка знать id.
     * Возвращает id.
     *
     * @return int
     */
    public function testAddOkForEdit(): int
    {
        // нужно добавить, чтоб занать наверняка существующий id
        // add - ok
        $params = [
            'text' => 'Ad for edit error',
            'banner' => 'http://banner.com/banner/image.jpg',
            'price' => 100,
            'limit' => 500,
        ];
        $item = $this->helper->prepareAddRequestDataValid($params);
        $res = $this->assertIsRequestResponseOk($item);
        // проверка того, что вернулись теже данные, что добавлялись
        $this->assertIsEqualsValueReponseDataAdd($params, $res['data']);
        $id = $res['data']['id'];
        return $id;
    }

    /**
     * Проверка валидных ошибок на запросы редактирования.
     *
     * @dataProvider dataProviderErrorValidEditRequest
     * @depends testAddOkForEdit
     */
    public function testErrorValidOnEditRequest($params, $result, $id)
    {
        $item = $this->helper->prepareEditRequestDataValid($id, $params);
        // проверка запроса
        $res = $this->assertIsRequestResponseErrorValid($item);
        // проверка кода и сообщения
        $this->assertEquals($res['code'], $result['code']);
        $this->assertEquals($res['message'], $result['message']);
    }

    /**
     * Проверка не успешного запроса редактирования с ошибкой параметра текста.
     *
     */
    // public function testErrorValidOnRelevantRequest()
    // {
    //      @TODO - только если не одной записи еще не было добавлено
    // }

    /**
     * Проверка не успешного запроса редактирования с ошибкой параметра id в аргументах URl.
     *
     */
    public function testErrorValidOnEditInvalidArgumentRequest()
    {
        $params = [
            'text' => 'Ad text',
            'banner' => 'http://banner.com/banner/image.png',
            'price' => 300,
            'limit' => 1000,
        ];
        $item = $this->helper->prepareEditRequestDataValid(0, $params);
        // проверка запроса
        $res = $this->assertIsRequestResponseErrorValid($item);
        // проверка кода и сообщения
        $this->assertEquals($res['code'], 400);
        $this->assertEquals($res['message'], 'Invalid argument');
    }

    /**
     * Провайдер данных для запросов с ожидаемой валидной ошибкой роута.
     *
     * первый аргемент: массив парамеров запроса: method, path, options
     * второй аргумент: массив параметров ожидаемого результата: code, message
     *
     * @return array
     */
    public function dataProviderErrorValidRequsetsWithInvalidRout()
    {
        return [
            [
                [
                    'method' => 'GET', 'path' => '/ads/invalid', 'options' => [],
                ],
                [
                    'code' => 404,
                    'message' => 'Invalid rout',
                ]
            ],
            [
                [
                    'method' => 'POST', 'path' => '/ads/invalid', 'options' => [],
                ],
                [
                    'code' => 404,
                    'message' => 'Invalid rout',
                ]
            ],
        ];
    }

    /**
     * @dataProvider dataProviderErrorValidRequsetsWithInvalidRout
     */
    public function testErrorValidOnRequestInvalidRout($params, $result)
    {
        $item = $this->helper->prepareRequserData($params['method'], $params['path'], $params['options']);
        // проверка запроса
        $res = $this->assertIsRequestResponseErrorValid($item);
        // проверка кода и сообщения
        $this->assertEquals($res['code'], $result['code']);
        $this->assertEquals($res['message'], $result['message']);
    }

    /**
     * Провайдер данных для запросов c валидными ошибками.
     *
     * первый аргемент: массив параметров запроса: method, path, options
     *
     * @return array
     */
    public function dataProviderForDataRequestErrorValid()
    {
        $generator = new DataGenerator();
        $data = $generator->generateArrayAddDataRequestErrorValid();
        return array_map(function ($item) {
            return [$item];
        }, $data);
    }

    /**
     * Тестирование запросов c валидными ошибками на сгенерированных данных.
     *
     * @dataProvider dataProviderForDataRequestErrorValid
     */
    public function testErrorValidRequestGenerated($item)
    {
        $this->assertIsRequestResponseErrorValid($item);
    }
}
