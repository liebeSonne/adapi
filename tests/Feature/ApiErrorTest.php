<?php

namespace Tests\Feature;

use Tests\Feature\ApiTestCase;

/**
 * Тесты запросов с валидной ошибкой.
 *
 */
class ApiErrorTest extends ApiTestCase
{
    /**
     * Проверка не успешного запроса добавления с ошибкой параметра текста.
     *
     */
    public function testErrorValidOnAddRequestInvalidText()
    {
        $params = [
            'text' => '',
            'banner' => 'http://banner.com/banner/image.png',
            'price' => 300,
            'limit' => 1000,
        ];
        $item = $this->helper->prepareAddRequestDataValid($params);
        // проверка запроса
        $res = $this->assertIsRequestResponseErrorValid($item);
        // проверка кода и сообщения
        $this->assertEquals($res['code'], 400);
        $this->assertEquals($res['message'], 'Invalid text');
    }

    /**
     * Проверка не успешного запроса добавления с ошибкой параметра баннера.
     *
     */
    public function testErrorValidOnAddRequestInvalidBanner()
    {
        $params = [
            'text' => 'Ad text',
            'banner' => 'invalid',
            'price' => 300,
            'limit' => 1000,
        ];
        $item = $this->helper->prepareAddRequestDataValid($params);
        // проверка запроса
        $res = $this->assertIsRequestResponseErrorValid($item);
        // проверка кода и сообщения
        $this->assertEquals($res['code'], 400);
        $this->assertEquals($res['message'], 'Invalid banner link');
    }

    /**
     * Проверка не успешного запроса добавления с ошибкой параметра стоимости.
     *
     */
    public function testErrorValidOnAddRequestInvalidPrice()
    {
        $params = [
            'text' => 'Ad text',
            'banner' => 'http://banner.com/banner/image.png',
            'price' => -100,
            'limit' => 1000,
        ];
        $item = $this->helper->prepareAddRequestDataValid($params);
        // проверка запроса
        $res = $this->assertIsRequestResponseErrorValid($item);
        // проверка кода и сообщения
        $this->assertEquals($res['code'], 400);
        $this->assertEquals($res['message'], 'Invalid price');
    }

    /**
     * Проверка не успешного запроса добавления с ошибкой параметра лимита.
     *
     */
    public function testErrorValidOnAddRequestInvalidLimit()
    {
        $params = [
            'text' => 'Ad text',
            'banner' => 'http://banner.com/banner/image.png',
            'price' => 300,
            'limit' => -1,
        ];
        $item = $this->helper->prepareAddRequestDataValid($params);
        // проверка запроса
        $res = $this->assertIsRequestResponseErrorValid($item);
        // проверка кода и сообщения
        $this->assertEquals($res['code'], 400);
        $this->assertEquals($res['message'], 'Invalid limit');
    }

    /**
     * Добавление записи для редактирования.
     * Чтоб наверняка знать id.
     * Возвращает id.
     *
     * @return int
     */
    protected function addOkForEdit(): int
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
     * Проверка не успешного запроса редактирования с ошибкой параметра текста.
     *
     */
    public function testErrorValidOnAddEditRequestInvalidText()
    {
        // add - ok
        $id = $this->addOkForEdit();

        // edit - error
        $params = [
            'text' => '',
            'banner' => 'http://banner.com/banner/image.png',
            'price' => 300,
            'limit' => 1000,
        ];
        $item = $this->helper->prepareEditRequestDataValid($id, $params);
        // проверка запроса
        $res = $this->assertIsRequestResponseErrorValid($item);
        // проверка кода и сообщения
        $this->assertEquals($res['code'], 400);
        $this->assertEquals($res['message'], 'Invalid text');
    }

    /**
     * Проверка не успешного запроса редактирования с ошибкой параметра баннера.
     *
     */
    public function testErrorValidOnAddEditRequestInvalidBanner()
    {
        // add - ok
        $id = $this->addOkForEdit();

        // edit - error
        $params = [
            'text' => 'Ad text',
            'banner' => 'invalid',
            'price' => 300,
            'limit' => 1000,
        ];
        $item = $this->helper->prepareEditRequestDataValid($id, $params);
        // проверка запроса
        $res = $this->assertIsRequestResponseErrorValid($item);
        // проверка кода и сообщения
        $this->assertEquals($res['code'], 400);
        $this->assertEquals($res['message'], 'Invalid banner link');
    }

    /**
     * Проверка не успешного запроса редактирования с ошибкой параметра стоимости.
     *
     */
    public function testErrorValidOnAddEditRequestInvalidPrice()
    {
        // add - ok
        $id = $this->addOkForEdit();

        // edit - error
        $params = [
            'text' => 'Ad text',
            'banner' => 'http://banner.com/banner/image.png',
            'price' => -100,
            'limit' => 1000,
        ];
        $item = $this->helper->prepareEditRequestDataValid($id, $params);
        // проверка запроса
        $res = $this->assertIsRequestResponseErrorValid($item);
        // проверка кода и сообщения
        $this->assertEquals($res['code'], 400);
        $this->assertEquals($res['message'], 'Invalid price');
    }

    /**
     * Проверка не успешного запроса редактирования с ошибкой параметра лимита.
     *
     */
    public function testErrorValidOnAddEditRequestInvalidLimit()
    {
        // add - ok
        $id = $this->addOkForEdit();

        // edit - error
        $params = [
            'text' => 'Ad text',
            'banner' => 'http://banner.com/banner/image.png',
            'price' => 300,
            'limit' => -1,
        ];
        $item = $this->helper->prepareEditRequestDataValid($id, $params);
        // проверка запроса
        $res = $this->assertIsRequestResponseErrorValid($item);
        // проверка кода и сообщения
        $this->assertEquals($res['code'], 400);
        $this->assertEquals($res['message'], 'Invalid limit');
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
     * Проверка не успешного GET запроса - отсутствие роута.
     *
     */
    public function testErrorValidOnGetInvalidRoutRequest()
    {
        $item = $this->helper->prepareRequserData('GET', '/ads/invalid');
        // проверка запроса
        $res = $this->assertIsRequestResponseErrorValid($item);
        // проверка кода и сообщения
        $this->assertEquals($res['code'], 404);
        $this->assertEquals($res['message'], 'Invalid rout');
    }

    /**
     * Проверка не успешного POST запроса - отсутствие ресурса.
     *
     */
    public function testErrorValidOnPostInvalidRoutRequest()
    {
        $item = $this->helper->prepareRequserData('POST', '/ads/invalid');
        // проверка запроса
        $res = $this->assertIsRequestResponseErrorValid($item);
        // проверка кода и сообщения
        $this->assertEquals($res['code'], 404);
        $this->assertEquals($res['message'], 'Invalid rout');
    }

    /**
     * Тестирование не успешных запроcв на сгенерированных данных.
     *
     */
    public function testErrorValidRequestGenerated()
    {
        $data = $this->generator->generateArrayAddDataRequestErrorValid();
        foreach ($data as $item) {
            $this->assertIsRequestResponseErrorValid($item);
        }
    }
}
