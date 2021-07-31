<?php

namespace Tests\Unit\Common\Response;

use App\Common\Response\Response;
use App\Common\Response\ResponseInterface;
use PHPUnit\Framework\TestCase;

class ResponseTest extends TestCase
{
    public function testAddHeader()
    {
        $response = new Response();
        $this->assertInstanceOf(ResponseInterface::class, $response->addHeader('header'));
    }

    public function testSetStatus()
    {
        $response = new Response();
        $this->assertInstanceOf(ResponseInterface::class, $response->setStatus(200));
    }

    public function testSetStatusText()
    {
        $response = new Response();
        $this->assertInstanceOf(ResponseInterface::class, $response->setStatusText('text'));
    }

    public function testSetBody()
    {
        $response = new Response();
        $this->assertInstanceOf(ResponseInterface::class, $response->setBody('boxy'));
    }

    public function testDisplay()
    {
        $body = '<b>html</b>';
        $header = 'Content-Type: application/json';

        $response = new Response();
        $response->setBody($body);
        $response->addHeader($header);
        $this->expectOutputString($body);
        $response->display();

        if (function_exists('xdebug_get_headers')) {
            $headers = xdebug_get_headers();
            $this->assertContains($header, $headers);
        }
    }

    public function testDisplayEmpty()
    {
        $response = new Response();
        $out = '';
        $this->expectOutputString($out);
        $response->display();
    }
}
