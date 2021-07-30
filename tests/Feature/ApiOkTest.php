<?php

namespace Tests\Feature;

use Tests\Feature\ApiTestCase;
use Tests\Feature\Helper\DataGenerator;

/**
 * Тесты успешных запрсов.
 *
 */
class ApiOkTest extends ApiTestCase
{
    /**
     * Проверка успешного запроса добавления.
     *
     */
    public function testOkOnAddRequest()
    {
        $params = [
            'text' => 'Ad text <b>html</b> 123',
            'banner' => 'http://banner.com/banner/image.png',
            'price' => 300,
            'limit' => 1000,
        ];
        $item = $this->helper->prepareAddRequestDataValid($params);
        $res = $this->assertIsRequestResponseOk($item);
        // проверка того, что вернулись теже данные, что добавлялись
        $this->assertIsEqualsValueReponseDataAdd($params, $res['data']);
        $id = $res['data']['id'];
        return $id;
    }

    /**
     * Проверка успешного запроса добавления и сделом за ним запроса редактирования.
     *
     * @depends testOkOnAddRequest
     */
    public function testOkOnAddEditRequest($id)
    {
        $params = [
            'text' => 'Ad for edit - eddited',
            'banner' => 'http://banner.com/banner/image_new.jpeg',
            'price' => 155,
            'limit' => 310,
        ];
        $item = $this->helper->prepareEditRequestDataValid($id, $params);
        $res = $this->assertIsRequestResponseOk($item);

        $params['id'] = $id;
        // проверка того, что после редактирования вернулись новые данные
        $this->assertIsEqualsValueReponseData($params, $res['data']);
    }

    /**
     * Проверка успешного запроса выборки для отображения.
     *
     * Должна быть добавлена хотябы одна запись, которая сможет бывть отображена.
     *
     * @depends testOkOnAddRequest
     */
    public function testOkOnAddRelevantRequest()
    {
        $item = $this->helper->prepareRelevantRequestDataValid();
        $this->assertIsRequestResponseOk($item);
    }

    /**
     * Провайдер данных для успешных запросов.
     *
     * первый аргемент: массив параметров запроса: method, path, options
     *
     * @return array
     */
    public function dataProviderGeneratedOkRequest(): array
    {
        $generator = new DataGenerator();
        $data = $generator->generateArrayAddDataRequestOk();
        return array_map(function ($item) {
            return [$item];
        }, $data);
    }

    /**
     * Тестирование успешных запросов на сгенерированных данных.
     *
     * @dataProvider dataProviderGeneratedOkRequest
     */
    public function testOkRequestGenerated($item)
    {
        $this->assertIsRequestResponseOk($item);
    }
}
