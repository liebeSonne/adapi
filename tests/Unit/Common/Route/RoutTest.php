<?php

namespace Tests\Unit\Common\Route;

use PHPUnit\Framework\TestCase;
use App\Common\Route\Rout;
use App\Common\Resources\ResourceInterface;
use App\Common\Resources\ResourcesInterface;
use App\Common\Resources\Exception\ResourceException;

class RoutTest extends TestCase
{
    public function testConstruct()
    {
        $method = 'GET';
        $pattern = '/^ads$/';
        $class = 'testClass';
        $action = 'testAction';

        $rout = new Rout($method, $pattern, $class, $action);

        $this->assertInstanceOf(Rout::class, $rout);
    }

    /**
     * Возвращает пары значений дял проверки isMatch().
     *
     * первый параметр - данные роута: method, pattern, class, action
     * второй параметр - данные проверки и результата: method, url, isMatch
     *
     * @return array
     */
    public function dataProviderIsMatch(): array
    {
        return [
            [
                [
                    'method' => 'GET',
                    'pattern' => '/^ads$/',
                    'class' => 'testClass',
                    'action' => 'testAction',
                ],
                [
                    'method' => 'GET',
                    'url' => 'ads',
                    'isMatch' => true,
                ]
            ],
            [
                [
                    'method' => 'GET',
                    'pattern' => '/^ads$/',
                    'class' => 'testClass',
                    'action' => 'testAction',
                ],
                [
                    'method' => 'GET',
                    'url' => 'ads/123',
                    'isMatch' => false,
                ]
            ],
            [
                [
                    'method' => 'GET',
                    'pattern' => '/^ads$/',
                    'class' => 'testClass',
                    'action' => 'testAction',
                ],
                [
                    'method' => 'POST',
                    'url' => 'ads',
                    'isMatch' => false,
                ]
            ],
        ];
    }

    /**
     * @dataProvider dataProviderIsMatch
     */
    public function testIsMatch($routData, $matchData)
    {
        $method = $routData['method'];
        $pattern = $routData['pattern'];
        $class = $routData['class'];
        $action = $routData['action'];

        $rout = new Rout($method, $pattern, $class, $action);

        $isMatch = $rout->isMatch($matchData['method'], $matchData['url']);
        $this->assertEquals($matchData['isMatch'], $isMatch);
    }

    /**
     * Возвращает пары значений дял проверки getArguments().
     *
     * первый параметр - данные роута: method, pattern, class, action
     * второй параметр - данные проверки и результата: url, arguments
     *
     * @return array
     */
    public function dataProviderGetArguments(): array
    {
        return [
            [
                [
                    'method' => 'GET',
                    'pattern' => '/^ads$/',
                    'class' => 'testClass',
                    'action' => 'testAction',
                ],
                [
                    'url' => 'ads',
                    'arguments' => [
                        0 => 'ads',
                    ],
                ]
            ],
            [
                [
                    'method' => 'GET',
                    'pattern' => '/^ads$/',
                    'class' => 'testClass',
                    'action' => 'testAction',
                ],
                [
                    'url' => 'ads/123',
                    'arguments' => [],
                ]
            ],
            [
                [
                    'method' => 'GET',
                    'pattern' => '/^ads\/(\d+)$/',
                    'class' => 'testClass',
                    'action' => 'testAction',
                ],
                [
                    'url' => 'ads/123',
                    'arguments' => [
                        0 => 'ads/123',
                        1 => '123',
                    ],
                ]
            ],
            [
                [
                    'method' => 'GET',
                    'pattern' => '/^ads\/(?<id>\d+)$/',
                    'class' => 'testClass',
                    'action' => 'testAction',
                ],
                [
                    'url' => 'ads/123',
                    'arguments' => [
                        0 => 'ads/123',
                        1 => '123',
                        'id' => '123',
                    ],
                ]
            ],
            [
                [
                    'method' => 'GET',
                    'pattern' => '/^ads\/(?<id>\d+)$/',
                    'class' => 'testClass',
                    'action' => 'testAction',
                ],
                [
                    'url' => 'ads',
                    'arguments' => [],
                ]
            ],
        ];
    }

    /**
     * @dataProvider dataProviderGetArguments
     */
    public function testGetArguments($routData, $rez)
    {
        $method = $routData['method'];
        $pattern = $routData['pattern'];
        $class = $routData['class'];
        $action = $routData['action'];

        $rout = new Rout($method, $pattern, $class, $action);

        $arguments = $rout->getArguments($rez['url']);
        $this->assertEquals($rez['arguments'], $arguments);
    }

    /**
     * Проверка вызова успешного ресурса.
     */
    public function testCallResource()
    {
        $method = 'GET';
        $pattern = '/^ads$/';
        $class = 'testClass';
        $action = 'testAction';

        $rout = new Rout($method, $pattern, $class, $action);

        $res = $this->createMock(ResourceInterface::class);
        $res->method('call')->will($this->returnValue(true));

        $resources = $this->createMock(ResourcesInterface::class);
        $resources->method('getResource')->will($this->returnValue($res));
        $url = 'ads';

        $this->assertNull($rout->callResource($resources, $url));
    }

    /**
     * Проверка вызова ресурса с исклчюением отсутствия ресурса.
     *
     */
    public function testCallResourceException()
    {
        $method = 'GET';
        $pattern = '/^ads$/';
        $class = 'testClass';
        $action = 'testAction';

        $rout = new Rout($method, $pattern, $class, $action);

        $resources = $this->createMock(ResourcesInterface::class);
        $resources->method('getResource')->will($this->returnValue(null));
        $url = 'ads';

        $this->expectException(ResourceException::class);
        $this->expectExceptionCode(404);
        $this->expectExceptionMessage('Invalid resource');
        $rout->callResource($resources, $url);
    }
}
