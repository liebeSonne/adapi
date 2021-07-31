<?php

namespace Tests\Unit\Common\Response;

use App\Common\Response\ApiResponse;
use App\Common\Response\ApiResponseInterface;
use PHPUnit\Framework\TestCase;

class ApiResponseTest extends TestCase
{
    /**
     * Провкрка установки всех параметров.
     *
     */
    public function testSet()
    {
        $response = $this->getMockForAbstractClass(ApiResponse::class);
        $rez = $response->set(400, 'message', ['data']);
        $this->assertInstanceOf(ApiResponseInterface::class, $rez);
    }

    /**
     * Проверка устаноуи кода.
     *
     */
    public function testSetCode()
    {
        $response = $this->getMockForAbstractClass(ApiResponse::class);
        $rez = $response->setCode(400);
        $this->assertInstanceOf(ApiResponseInterface::class, $rez);
    }

    /**
     * Проверка установки сообщения.
     *
     */
    public function testSetMessage()
    {
        $response = $this->getMockForAbstractClass(ApiResponse::class);
        $rez = $response->setMessage('message');
        $this->assertInstanceOf(ApiResponseInterface::class, $rez);
    }

    /**
     * Проверка установки данных.
     *
     */
    public function testSetData()
    {
        $response = $this->getMockForAbstractClass(ApiResponse::class);
        $rez = $response->setData(['data']);
        $this->assertInstanceOf(ApiResponseInterface::class, $rez);
    }

    /**
     * Проверка отображения.
     *
     */
    public function testDisplay()
    {
        $response = $this->getMockForAbstractClass(ApiResponse::class);
        $response->method('display')->will($this->returnCallback(function () {
            echo "test";
        }));
        $this->expectOutputString('test');
        $response->display();
    }
}
