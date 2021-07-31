<?php

namespace Tests\Unit\Api;

use PHPUnit\Framework\TestCase;
use App\Api\AdsApi;
use App\Api\Exception\ApiException;
use App\Common\Request\RequestInterface;
use App\Common\Response\ApiResponseInterface;
use App\Model\Ad;
use App\Repository\AdsRepository;

class AdsApiTest extends TestCase
{

    /**
     * Проверка конструктора.
     *
     */
    public function testConstruct()
    {
        $request = $this->createMock(RequestInterface::class);
        $response = $this->createMock(ApiResponseInterface::class);
        $repository = $this->createMock(AdsRepository::class);

        $api = new AdsApi($request, $response, $repository);

        $this->assertInstanceOf(AdsApi::class, $api);
    }

    /**
     * Проверка успешного добавления.
     *
     * @runInSeparateProcess
     */
    public function testAddAdOk()
    {
        $request = $this->createMock(RequestInterface::class);
        $response = $this->createMock(ApiResponseInterface::class);
        $repository = $this->createMock(AdsRepository::class);

        $id = 1;
        $post = [
            'text' => 'Ad text',
            'banner' => 'http://banner.com/image.png',
        ];
        $outData = [
            'code' => 200,
            'message' => 'Ok',
            'data' => [
                'id' => $id,
                'text' => $post['text'],
                'banner' => $post['banner'],
            ],
        ];
        $out = json_encode($outData);

        $request->method('getRequestParams')->will($this->returnValue($post));
        $repository->method('add')->will($this->returnValue($id));
        $response->method('display')->will($this->returnCallback(function () use ($out) {
            echo $out;
        }));

        $api = new AdsApi($request, $response, $repository);

        $this->expectOutputString($out);

        $api->addAd();
    }

    /**
     * Проверка ошибки при добвлении.
     *
     * @runInSeparateProcess
     */
    public function testAddAdError()
    {
        $request = $this->createMock(RequestInterface::class);
        $response = $this->createMock(ApiResponseInterface::class);
        $repository = $this->createMock(AdsRepository::class);

        $post = [
            'text' => '',
            'banner' => 'http://banner.com/image.png',
        ];
        $outData = [
            'code' => 400,
            'message' => 'Invalid text',
            'data' => [],
        ];
        $out = json_encode($outData);

        $request->method('getRequestParams')->will($this->returnValue($post));
        $response->method('display')->will($this->returnCallback(function () use ($out) {
            echo $out;
        }));

        $api = new AdsApi($request, $response, $repository);

        $this->expectOutputString($out);

        $api->addAd();
    }

    /**
     * Проверка исключения при добавлени.
     *
     */
    public function testAddAdApiException()
    {
        $request = $this->createMock(RequestInterface::class);
        $response = $this->createMock(ApiResponseInterface::class);
        $repository = $this->createMock(AdsRepository::class);

        $post = [
            'text' => 'text',
            'banner' => 'http://banner.com/image.png',
        ];
        $request->method('getRequestParams')->will($this->returnValue($post));
        $repository->method('add')->will($this->returnValue(0));

        $api = new AdsApi($request, $response, $repository);

        $this->expectException(ApiException::class);
        $this->expectExceptionCode(500);
        $this->expectExceptionMessage('Can not add');

        $api->addAd();
    }

    /**
     * Проверка успешного редактирования.
     *
     * @runInSeparateProcess
     */
    public function testEditAdOk()
    {
        $request = $this->createMock(RequestInterface::class);
        $response = $this->createMock(ApiResponseInterface::class);
        $repository = $this->createMock(AdsRepository::class);

        $id = 1;
        $post = [
            'text' => 'Ad text',
            'banner' => 'http://banner.com/image.png',
        ];
        $outData = [
            'code' => 200,
            'message' => 'Ok',
            'data' => [
                'id' => $id,
                'text' => $post['text'],
                'banner' => $post['banner'],
            ],
        ];
        $out = json_encode($outData);

        $request->method('getRequestParams')->will($this->returnValue($post));
        $repository->method('edit')->will($this->returnValue(true));
        $response->method('display')->will($this->returnCallback(function () use ($out) {
            echo $out;
        }));

        $api = new AdsApi($request, $response, $repository);

        $this->expectOutputString($out);

        $api->editAd(['id' => $id]);
    }

    /**
     * Проверка ошибки при редактировании.
     *
     * @runInSeparateProcess
     */
    public function testEditAdError()
    {
        $request = $this->createMock(RequestInterface::class);
        $response = $this->createMock(ApiResponseInterface::class);
        $repository = $this->createMock(AdsRepository::class);

        $id = 1;
        $post = [
            'text' => '',
            'banner' => 'http://banner.com/image.png',
        ];
        $outData = [
            'code' => 400,
            'message' => 'Invalid text',
            'data' => [],
        ];
        $out = json_encode($outData);

        $request->method('getRequestParams')->will($this->returnValue($post));
        $response->method('display')->will($this->returnCallback(function () use ($out) {
            echo $out;
        }));

        $api = new AdsApi($request, $response, $repository);

        $this->expectOutputString($out);

        $api->editAd(['id' => $id]);
    }

    /**
     * Проверка исклчюения аргумента при редактировании.
     *
     */
    public function testEditAdExceptionId()
    {
        $request = $this->createMock(RequestInterface::class);
        $response = $this->createMock(ApiResponseInterface::class);
        $repository = $this->createMock(AdsRepository::class);

        $api = new AdsApi($request, $response, $repository);

        $this->expectException(ApiException::class);
        $this->expectExceptionCode(400);
        $this->expectExceptionMessage('Invalid argument');

        $api->editAd();
    }

    /**
     * Проверка исключения о невозмонжости отредактировать при редпктировании.
     *
     */
    public function testEditAdExceptionEdit()
    {
        $request = $this->createMock(RequestInterface::class);
        $response = $this->createMock(ApiResponseInterface::class);
        $repository = $this->createMock(AdsRepository::class);

        $id = 1;
        $post = [
            'text' => 'text',
            'banner' => 'http://banner.com/image.png',
        ];
        $request->method('getRequestParams')->will($this->returnValue($post));
        $repository->method('edit')->will($this->returnValue(false));

        $api = new AdsApi($request, $response, $repository);

        $this->expectException(ApiException::class);
        $this->expectExceptionCode(500);
        $this->expectExceptionMessage('Can not edit');

        $api->editAd(['id' => $id]);
    }

    /**
     * Проверка успешной выборки для показа.
     *
     * @runInSeparateProcess
     */
    public function testRelevantAd()
    {
        $request = $this->createMock(RequestInterface::class);
        $response = $this->createMock(ApiResponseInterface::class);
        $repository = $this->createMock(AdsRepository::class);

        $data = [
            'id' => 1,
            'text' => 'Ad text',
            'banner' => 'http://banner.com/image.png',
        ];
        $ad = new Ad();
        $ad->id = $data['id'];
        $ad->text = $data['text'];
        $ad->banner = $data['banner'];
        $outData = [
            'code' => 200,
            'message' => 'Ok',
            'data' => [
                'id' => $data['id'],
                'text' => $data['text'],
                'banner' => $data['banner'],
            ],
        ];
        $out = json_encode($outData);

        $repository->method('getRelevant')->will($this->returnValue($ad));
        //$repository->method('onShow')->will($this->returnValue(ture));
        $response->method('display')->will($this->returnCallback(function () use ($out) {
            echo $out;
        }));

        $api = new AdsApi($request, $response, $repository);

        $this->expectOutputString($out);

        $api->relevantAd();
    }

    /**
     * Проверка исключения при выборки для отображения.
     *
     */
    public function testRelevantAdApiException()
    {
        $request = $this->createMock(RequestInterface::class);
        $response = $this->createMock(ApiResponseInterface::class);
        $repository = $this->createMock(AdsRepository::class);

        $repository->method('getRelevant')->will($this->returnValue(null));

        $api = new AdsApi($request, $response, $repository);

        $this->expectException(ApiException::class);
        $this->expectExceptionCode(404);
        $this->expectExceptionMessage('Can not show');

        $api->relevantAd();
    }
}
