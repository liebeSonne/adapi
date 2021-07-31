<?php

namespace Tests\Unit\Repository\Strategy;

use PHPUnit\Framework\TestCase;
use App\Repository\Strategy\DefaultDBRelevantStrategy;
use App\Service\DBInterface;
use App\Model\Ad;

class DefaultDBRelevantStrategyTest extends TestCase
{
    /**
     * Проверка конструктора.
     *
     */
    public function testConstruct()
    {
        $db = $this->createMock(DBInterface::class);
        $strategy = new DefaultDBRelevantStrategy($db);

        $this->assertInstanceOf(DefaultDBRelevantStrategy::class, $strategy);
    }

    /**
     * Проверка успешной выборки одного экземпляра.
     */
    public function testSelectOneOk()
    {
        $data = [
            'id' => 1,
            'text' => 'text',
            'banner' => 'http://banner.com/image.png',
        ];
        $ad = new Ad($data);
        $db = $this->createMock(DBInterface::class);
        $db->method('fetchRow')->will($this->returnValue($ad->toArray()));
        $strategy = new DefaultDBRelevantStrategy($db);

        $selected = $strategy->selectOne();
        $this->assertInstanceOf(Ad::class, $selected);
        $this->assertEquals($ad, $selected);
    }

    /**
     * Проверка не успешной выборки одного экземпляра.
     *
     */
    public function testSelectOneNull()
    {
        $db = $this->createMock(DBInterface::class);
        $db->method('fetchRow')->will($this->returnValue(null));
        $strategy = new DefaultDBRelevantStrategy($db);

        $selected = $strategy->selectOne();
        $this->assertNull($selected);
    }
}
