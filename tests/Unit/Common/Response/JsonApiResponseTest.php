<?php

namespace Tests\Unit\Common\Response;

use App\Common\Response\JsonApiResponse;
use PHPUnit\Framework\TestCase;

class JsonApiResponseTest extends TestCase
{
    public function testDisplay()
    {
        $response = new JsonApiResponse();
        $response->set(400, 'message', []);

        $out = '{"code":400,"message":"message","data":[]}';
        $this->expectOutputString($out);
        $response->display();

        if (function_exists('xdebug_get_headers')) {
            $headers = xdebug_get_headers();
            $this->assertContains('Content-Type: application/json', $headers);
        }
    }

    public function testDisplayEmptySet()
    {
        $response = new JsonApiResponse();

        $out = '{"code":200,"message":"Ok","data":[]}';
        $this->expectOutputString($out);
        $response->display();

        if (function_exists('xdebug_get_headers')) {
            $headers = xdebug_get_headers();
            $this->assertContains('Content-Type: application/json', $headers);
        }
    }
}
