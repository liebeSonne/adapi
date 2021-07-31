<?php

namespace Tests\Unit\Api;

use PHPUnit\Framework\TestCase;
use App\Api\ApiBase;
use App\Api\Exception\ApiException;
use App\Common\Request\RequestInterface;
use App\Common\Response\ApiResponseInterface;

class ApiBaseTest extends TestCase
{
    /**
     * Проверка конструктора.
     *
     */
    public function testConstruct()
    {
        $request = $this->createMock(RequestInterface::class);
        $response = $this->createMock(ApiResponseInterface::class);

        $api = new ApiBase($request, $response);

        $this->assertInstanceOf(ApiBase::class, $api);
    }

    /**
     * Проверка запуска не существующего метода сисключением.
     *
     */
    public function testCallException()
    {
        $request = $this->createMock(RequestInterface::class);
        $response = $this->createMock(ApiResponseInterface::class);

        $api = new ApiBase($request, $response);

        $this->expectException(ApiException::class);
        $this->expectExceptionCode(405);
        $this->expectExceptionMessage('Invalid resource method');
        $api->call('notExistMethod');
    }

    /**
     * Проверка запуска существующего метода.
     *
     */
    public function testCall()
    {
        $api = $this->createMock(ApiBase::class);
        $api->method('call')->will($this->returnValue(null));

        $this->assertNull($api->call('existMethod'));
    }

    /**
     * Проверка запуска не существующего метода сисключением.
     *
     */
    public function testCallOk()
    {
        $request = $this->createMock(RequestInterface::class);
        $response = $this->createMock(ApiResponseInterface::class);

        $api = new ApiBase($request, $response);

        $this->assertNull($api->call('default'));
    }
}
