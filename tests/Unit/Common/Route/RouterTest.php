<?php

namespace Tests\Unit\Common\Route;

use App\Common\Request\RequestInterface;
use App\Common\Route\Rout;
use PHPUnit\Framework\TestCase;
use App\Common\Route\Router;
use App\Common\Route\RouterInterface;
use App\Common\Route\RoutInterface;

class RouterTest extends TestCase
{
    /**
     * Проверка метода регистрации.
     *
     */
    public function testRegMethod()
    {
        $router = new Router();
        $rout = $this->createMock(RoutInterface::class);

        $this->assertInstanceOf(RouterInterface::class, $router->reg($rout));
    }

    /**
     * Проверка метода поиска роута.
     *
     */
    public function testFindRoutMethod()
    {
        $router = new Router();

        $rout = $this->createMock(Rout::class);
        $rout->method('isMatch')->will($this->returnValue(true));
        $router->reg($rout);

        $request = $this->createMock(RequestInterface::class);

        $this->assertEquals($rout, $router->findRout($request));
    }

    /**
     * Проверка метода поиска роута без результата.
     *
     */
    public function testFindRoutMethodNull()
    {
        $router = new Router();

        $request = $this->createMock(RequestInterface::class);

        $this->assertNull($router->findRout($request));
    }
}
