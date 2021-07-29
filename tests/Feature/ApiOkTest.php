<?php

namespace Tests\Feature;

use Tests\Feature\ApiTestCase;

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
    }

    /**
     * Проверка успешного запроса добавления и сделом за ним запроса редактирования.
     *
     * Чтоб наверняка знать о наличии идентификатора, сначало нужно добавтиь запись.
     *
     */
    public function testOkOnAddEditRequest()
    {
        // add
        $params = [
            'text' => 'Ad for edit',
            'banner' => 'http://banner.com/banner/image.jpg',
            'price' => 100,
            'limit' => 500,
        ];
        $item = $this->helper->prepareAddRequestDataValid($params);
        $res = $this->assertIsRequestResponseOk($item);
        // проверка того, что вернулись теже данные, что добавлялись
        $this->assertIsEqualsValueReponseDataAdd($params, $res['data']);
        $id = $res['data']['id'];

        // edit
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
     * Перед отоюражение должна доабвиться хотябы одна запись которая будет отображена.
     *
     */
    public function testOkOnAddRelevantRequest()
    {
        // add
        $params = [
            'text' => 'Ad on add relevant',
            'banner' => 'http://banner.com/banner/image.png',
            'price' => 100,
            'limit' => 500,
        ];
        $item = $this->helper->prepareAddRequestDataValid($params);
        $this->assertIsRequestResponseOk($item);

        // relevant
        $item = $this->helper->prepareRelevantRequestDataValid();
        $this->assertIsRequestResponseOk($item);
    }

    /**
     * Тестирование успешных запросов на сгенерированных данных.
     *
     */
    public function testOkRequestGenerated()
    {
        $data = $this->generator->generateArrayAddDataRequestOk();
        foreach ($data as $item) {
            $this->assertIsRequestResponseOk($item);
        }
    }
}
