<?php

namespace Tests\Feature;

use PHPUnit\Framework\TestCase;
use Tests\Feature\Helper\DataGenerator;
use Tests\Feature\Helper\ApiHelper;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;

/**
 * Класс реализующий базовые методы для прверок API.
 *
 */
class ApiTestCase extends TestCase
{
    /**
     * @var GuzzleHttp\Client
     */
    protected $client;

    /**
     * @var DataGenerator
     */
    protected $generator;

    /**
     * @var ApiHelper
     */
    protected $helper;

    protected function setUp(): void
    {
        $this->generator = new DataGenerator();
        $this->helper = new ApiHelper();
        $this->client = new Client([
            'base_uri' => $this->getBaseUrl(),
        ]);
        parent::setUp();
    }

    /**
     * @return string
     */
    public function getBaseUrl()
    {
        return (string) $_ENV['api_base_url'] ?? '';
    }

    /**
     * Выполнение запроса.
     *
     * $item: method, path, options
     *
     * @param array $item
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function request($item)
    {
        try {
            return $this->client->request($item['method'], $item['path'], $item['options']);
        } catch (ClientException $e) {
            return $e->getResponse();
        } catch (ServerException $e) {
            return $e->getResponse();
        }
    }

    /**
     * Проверяет данные ответа на успешность.
     *
     * @param array $res
     */
    public function assertIsValueResponseOk(array $res)
    {
        // проверка значений
        $this->assertEquals(200, $res['code']);
        $this->assertEquals('Ok', $res['message']);
    }

    /**
     * Проверяет данные ответа на соответствие ошибке.
     *
     * @param array $res
     */
    public function assertIsValueResponseError(array $res)
    {
        // код один из допустимых кодов ошибки
        $codeMessages = $this->generator->getArrayMessagesByCodeError();
        $this->assertContains($res['code'], array_keys($codeMessages));

        // сообщение соответствует коду
        $this->assertContains($res['message'], $codeMessages[$res['code']]);

        // проверка данных
        $this->assertEquals([], $res['data']);
    }

    /**
     * Проверяет структуру ответа.
     *
     * @param mixed $res
     */
    public function assertIsFormatResponse($res)
    {
        $this->assertIsArray($res);

        // проверка структуры ответа
        $this->assertArrayHasKey("message", $res);
        $this->assertArrayHasKey("code", $res);
        $this->assertArrayHasKey("data", $res);

        $this->assertIsFormatResponseData($res['data']);
    }

    /**
     * Проверяет структуру ответа без проверки структуры data.
     *
     * @param mixed $res
     */
    public function assertIsFormatResponseNoData($res)
    {
        $this->assertIsArray($res);

        // проверка структуры ответа
        $this->assertArrayHasKey("message", $res);
        $this->assertArrayHasKey("code", $res);
        $this->assertArrayHasKey("data", $res);
    }

    /**
     * Проверяет структуру данных ответа в data.
     *
     * @param mixed $data
     */
    public function assertIsFormatResponseData($data)
    {
        $this->assertIsArray($data);

        $this->assertArrayHasKey("id", $data);
        $this->assertArrayHasKey("text", $data);
        $this->assertArrayHasKey("banner", $data);

        $this->assertIsNumeric($data['id']);
        $this->assertIsString($data['text']);
        $this->assertIsString($data['banner']);
    }

    /**
     * Проверка соответствия значений ответов в  data после добавления.
     *
     * После доабвления не проверяем только id, т.к. его не добавляли.
     *
     * @param array $expected
     * @param array $actual
     */
    public function assertIsEqualsValueReponseDataAdd(array $expected, array $actual)
    {
        $this->assertEquals($expected['text'], $actual['text']);
        $this->assertEquals($expected['banner'], $actual['banner']);
    }

    /**
     * Проверка соответствия значений ответов в  data.
     *
     * @param array $expected
     * @param array $actual
     */
    public function assertIsEqualsValueReponseData(array $expected, array $actual)
    {
        $this->assertEquals($expected['id'], $actual['id']);
        $this->assertEquals($expected['text'], $actual['text']);
        $this->assertEquals($expected['banner'], $actual['banner']);
    }

    /**
     * Проверка успешного запроса.
     * Возвращает результаты запроса.
     *
     * $item: method, path, options
     *
     * @param array $item
     * @return mixed
     */
    public function assertIsRequestResponseOk($item)
    {
        $response = $this->request($item);
        $code = $response->getStatusCode();
        $header = $response->getHeaderLine('Content-Type');
        $json = $response->getBody()->getContents();

        // проверка кода статуса ответа
        $this->assertEquals(200, $code);
        // проверка заголовка
        $this->assertEquals('application/json', $header);

        // проверка, что пришел именно json
        $res = json_decode($json, true);
        $this->assertEquals(JSON_ERROR_NONE, json_last_error());

        // проверка стурктуры
        $this->assertIsFormatResponse($res);
        // проверка данных
        $this->assertIsValueResponseOk($res);

        return $res;
    }

    /**
     * Проверка запроса с валидной ошибкой.
     * Возвращает результат запроса.
     *
     * @param array $item
     * @return mixed
     */
    public function assertIsRequestResponseErrorValid($item)
    {
        $response = $this->request($item);
        $code = $response->getStatusCode();
        $header = $response->getHeaderLine('Content-Type');
        $json = $response->getBody()->getContents();

        // проверка кода статуса ответа
        $this->assertEquals(200, $code);
        // проверка заголовка
        $this->assertEquals('application/json', $header);

        // проверка, что пришел именно json
        $res = json_decode($json, true);
        $this->assertEquals(JSON_ERROR_NONE, json_last_error());

        // проверка стурктуры
        $this->assertIsFormatResponseNoData($res);
        // проверка данных
        $this->assertIsValueResponseError($res);

        return $res;
    }

    /**
     * Проверка запроса с не валидными ошибками ошибкой.
     * Возвращает результат запроса.
     *
     * @param array $item
     * @return mixed
     */
    public function assertIsRequestResponseErrorInvalid($item)
    {
        $response = $this->request($item);
        $code = $response->getStatusCode();
        $data = $response->getBody()->getContents();

        // проверка кода статуса ответа
        $this->assertNotEquals(200, $code);

        // заголовк и тело ответа не можем предсказать

        return $data;
    }
}
