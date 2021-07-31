<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\App;
use App\Api\Exception\ApiException;
use App\Common\Request\RequestInterface;
use App\Common\Resources\Exception\ResourceException;
use App\Common\Resources\ResourcesInterface;
use App\Common\Response\ApiResponseInterface;
use App\Common\Route\Exception\RoutException;
use App\Common\Route\RouterInterface;
use App\Common\Route\RoutInterface;

class AppTest extends TestCase
{
    /**
     * Проверка консруктора через mock объекты.
     *
     */
    public function testConstruct()
    {
        $request = $this->createMock(RequestInterface::class);
        $response = $this->createMock(ApiResponseInterface::class);
        $resources = $this->createMock(ResourcesInterface::class);
        $router = $this->createMock(RouterInterface::class);

        $app = new App($request, $response, $resources, $router);

        $this->assertInstanceOf(App::class, $app);

        return $app;
    }

    /**
     * Проверка метода установки режима.
     *
     * @depends testConstruct
     */
    public function testSetModeMethod(App $app)
    {
        $mode = 'dev';
        $app->setMode($mode);

        $this->assertNull($app->setMode($mode));
    }

    /**
     * Проверка запуска с ошибкой роута.
     */
    public function testRunErrorInvalidRout()
    {
        $request = $this->createMock(RequestInterface::class);
        $response = $this->createMock(ApiResponseInterface::class);
        $resources = $this->createMock(ResourcesInterface::class);
        $router = $this->createMock(RouterInterface::class);

        $out = '{"code":404,"message":"Invalid rout","data":[]}';

        $router->method('findRout')->will($this->returnValue(null));
        $response->method('display')->will($this->returnCallback(function () use ($out) {
            echo $out;
        }));

        $app = new App($request, $response, $resources, $router);

        $this->expectOutputString($out);
        $app->run();
    }

    /**
     * Проверка успешного запуска.
     *
     */
    public function testRunAppOk()
    {
        $request = $this->createMock(RequestInterface::class);
        $response = $this->createMock(ApiResponseInterface::class);
        $resources = $this->createMock(ResourcesInterface::class);
        $router = $this->createMock(RouterInterface::class);
        $rout = $this->createMock(RoutInterface::class);

        $out = '{"code":200,"message":"Ok","data":["id": 1,"text":"text","banner":"http://banner.com/image.png"]}';

        $router->method('findRout')->will($this->returnValue($rout));
        $response->method('display')->will($this->returnCallback(function () use ($out) {
            echo $out;
        }));

        $app = new App($request, $response, $resources, $router);

        $this->assertNull($app->run());
    }

    /**
     * Проверка запуска с ошибкой исключения ApiException.
     *
     */
    public function testRunErrorApiException()
    {
        $request = $this->createMock(RequestInterface::class);
        $response = $this->createMock(ApiResponseInterface::class);
        $resources = $this->createMock(ResourcesInterface::class);
        $router = $this->createMock(RouterInterface::class);
        $rout = $this->createMock(RoutInterface::class);

        $outData = [
            'code' => 400,
            'message' => 'Error',
            'data' => [],
        ];
        $out = json_encode($outData);
        $e = new ApiException($outData['message'], $outData['code']);

        $router->method('findRout')->will($this->returnValue($rout));
        $rout->method('callResource')->will($this->throwException($e));
        $response->method('display')->will($this->returnCallback(function () use ($out) {
            echo $out;
        }));

        $app = new App($request, $response, $resources, $router);

        $this->expectOutputString($out);
        $app->run();
    }

    /**
     * Проверка запуска с ошибкой исключения ResourceException.
     *
     */
    public function testRunErrorResourceException()
    {
        $request = $this->createMock(RequestInterface::class);
        $response = $this->createMock(ApiResponseInterface::class);
        $resources = $this->createMock(ResourcesInterface::class);
        $router = $this->createMock(RouterInterface::class);
        $rout = $this->createMock(RoutInterface::class);

        $outData = [
            'code' => 400,
            'message' => 'Error',
            'data' => [],
        ];
        $out = json_encode($outData);
        $e = new ResourceException($outData['message'], $outData['code']);

        $router->method('findRout')->will($this->returnValue($rout));
        $rout->method('callResource')->will($this->throwException($e));
        $response->method('display')->will($this->returnCallback(function () use ($out) {
            echo $out;
        }));

        $app = new App($request, $response, $resources, $router);

        $this->expectOutputString($out);
        $app->run();
    }

    /**
     * Проверка запуска с ошибкой исключения RoutException.
     *
     */
    public function testRunErrorRoutException()
    {
        $request = $this->createMock(RequestInterface::class);
        $response = $this->createMock(ApiResponseInterface::class);
        $resources = $this->createMock(ResourcesInterface::class);
        $router = $this->createMock(RouterInterface::class);
        $rout = $this->createMock(RoutInterface::class);

        $outData = [
            'code' => 400,
            'message' => 'Error',
            'data' => [],
        ];
        $out = json_encode($outData);
        $e = new RoutException($outData['message'], $outData['code']);

        $router->method('findRout')->will($this->throwException($e));
        $response->method('display')->will($this->returnCallback(function () use ($out) {
            echo $out;
        }));

        $app = new App($request, $response, $resources, $router);

        $this->expectOutputString($out);
        $app->run();
    }

     /**
     * Проверка запуска с ошибкой исключения Exception.
     *
     */
    public function testRunErrorException()
    {
        $request = $this->createMock(RequestInterface::class);
        $response = $this->createMock(ApiResponseInterface::class);
        $resources = $this->createMock(ResourcesInterface::class);
        $router = $this->createMock(RouterInterface::class);
        $rout = $this->createMock(RoutInterface::class);

        $outData = [
            'code' => 500,
            'message' => 'Server Error',
            'data' => [],
        ];
        $out = json_encode($outData);
        $e = new \Exception($outData['message'], $outData['code']);

        $router->method('findRout')->will($this->returnValue($rout));
        $rout->method('callResource')->will($this->throwException($e));
        $response->method('display')->will($this->returnCallback(function () use ($out) {
            echo $out;
        }));

        $app = new App($request, $response, $resources, $router);

        $this->expectOutputString($out);
        $app->run();
    }

     /**
     * Проверка запуска с исключением Exception.
     *
     */
    public function testRunException()
    {
        $request = $this->createMock(RequestInterface::class);
        $response = $this->createMock(ApiResponseInterface::class);
        $resources = $this->createMock(ResourcesInterface::class);
        $router = $this->createMock(RouterInterface::class);
        $rout = $this->createMock(RoutInterface::class);

        $outData = [
            'code' => 500,
            'message' => 'Server Error',
        ];

        $e = new \Exception($outData['message'], $outData['code']);

        $router->method('findRout')->will($this->returnValue($rout));
        $rout->method('callResource')->will($this->throwException($e));

        $app = new App($request, $response, $resources, $router);
        $app->setMode('dev');

        $this->expectException(\Exception::class);
        $this->expectExceptionCode($outData['code']);
        $this->expectExceptionMessage($outData['message']);

        $app->run();
    }
}
