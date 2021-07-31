<?php

namespace Test\Unit\Common\Resources;

use PHPUnit\Framework\TestCase;
use App\Common\Resources\ResourceInterface;
use App\Common\Resources\Resources;
use App\Common\Resources\ResourcesInterface;

class ResourcesTest extends TestCase
{
    public function testRegMethod()
    {
        $resources = new Resources();

        $class = 'resClass';
        $res = $this->createMock(ResourceInterface::class);

        $rez = $resources->reg($class, $res);

        $this->assertInstanceOf(ResourcesInterface::class, $rez);
    }

    public function testGetResource()
    {
        $resources = new Resources();

        $class = 'resClass';
        $res = $this->createMock(ResourceInterface::class);
        $resources->reg($class, $res);

        $this->assertEquals($res, $resources->getResource($class));
        $this->assertEquals(null, $resources->getResource('noClass'));
    }
}
