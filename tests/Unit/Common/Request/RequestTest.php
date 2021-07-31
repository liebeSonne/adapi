<?php

namespace Tests\Unit\Common\Request;

use App\Common\Request\Request;
use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase
{
    public function testGetMethod()
    {
        $request = new Request();
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $this->assertEquals('POST', $request->getMethod());
    }

    public function testGetQueryParams()
    {
        $request = new Request();
        $_GET = ['a' => 'param'];
        $this->assertEquals($_GET, $request->getQueryParams());
    }

    public function testGetRequestParams()
    {
        $request = new Request();
        $_POST = ['a' => 'param'];
        $this->assertEquals($_POST, $request->getRequestParams());
    }

    /**
     * Возвращает пары: URI и ожидаемый getUrl()
     *
     * @return array
     */
    public function dataProviderRequestUri(): array
    {
        return [
            ['ads/relevant','ads/relevant'],
            ['ads/relevant/','ads/relevant'],
            ['/ads/relevant/','ads/relevant'],
            ['/ads/relevant','ads/relevant'],
        ];
    }

    /**
     * @dataProvider dataProviderRequestUri
     */
    public function testGetUrl($uri, $url)
    {
        $request = new Request();
        $_SERVER['REQUEST_URI'] = $uri;
        $this->assertEquals($url, $request->getUrl());
    }
}
