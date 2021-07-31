<?php

namespace Tests\Unit\Repository;

use App\Model\Ad;
use App\Repository\AdsRepository;
use App\Repository\Strategy\RelevantStrategyInterface;
use App\Service\DBInterface;
use PHPUnit\Framework\TestCase;

class AdsRepositoryTest extends TestCase
{
    /**
     * Проверка конструктора.
     *
     */
    public function testConstruct()
    {
        $db = $this->createMock(DBInterface::class);
        $strategy = $this->createMock(RelevantStrategyInterface::class);

        $rep = new AdsRepository($db, $strategy);

        $this->assertInstanceOf(AdsRepository::class, $rep);
    }

    /**
     * Проверка конструктора, без объекта стратегии.
     *
     */
    public function testConstructNoStrategy()
    {
        $db = $this->createMock(DBInterface::class);

        $rep = new AdsRepository($db);

        $this->assertInstanceOf(AdsRepository::class, $rep);
    }

    /**
     * Проверка успешного доабвления.
     *
     */
    public function testAddOk()
    {
        $db = $this->createMock(DBInterface::class);
        $id = 10;
        $db->method('insert')->will($this->returnValue($id));

        $rep = new AdsRepository($db);

        $item = $this->createMock(Ad::class);

        $this->assertEquals($id, $rep->add($item));
    }

    /**
     * Проверка не успешного доабвления.
     *
     */
    public function testAddNotOk()
    {
        $db = $this->createMock(DBInterface::class);
        $id = 0;
        $db->method('insert')->will($this->returnValue($id));

        $rep = new AdsRepository($db);

        $item = $this->createMock(Ad::class);

        $this->assertEquals($id, $rep->add($item));
    }

    /**
     * Проверка успешного редактирования.
     *
     */
    public function testEditOk()
    {
        $db = $this->createMock(DBInterface::class);
        $db->method('query')->will($this->returnValue(true));

        $rep = new AdsRepository($db);

        $id = 1;
        $item = $this->createMock(Ad::class);

        $this->assertTrue($rep->edit($id, $item));
    }

    /**
     * Проверка не успешного редактирования.
     *
     */
    public function testEditNotOk()
    {
        $db = $this->createMock(DBInterface::class);
        $db->method('query')->will($this->returnValue(false));

        $rep = new AdsRepository($db);

        $id = 1;
        $item = $this->createMock(Ad::class);

        $this->assertFalse($rep->edit($id, $item));
    }

    /**
     * Проверка успешного вызова при показе.
     *
     */
    public function testOnShowOk()
    {
        $db = $this->createMock(DBInterface::class);
        $db->method('query')->will($this->returnValue(true));

        $rep = new AdsRepository($db);

        $item = $this->createMock(Ad::class);

        $this->assertTrue($rep->onShow($item));
    }

    /**
     * Проверка не успешного вызова при показе.
     *
     */
    public function testOnShowNotk()
    {
        $db = $this->createMock(DBInterface::class);
        $db->method('query')->will($this->returnValue(false));

        $rep = new AdsRepository($db);

        $item = $this->createMock(Ad::class);

        $this->assertFalse($rep->onShow($item));
    }

    /**
     * Проверка успешной выборки записи.
     *
     */
    public function testGetRowOk()
    {
        $db = $this->createMock(DBInterface::class);
        $data = ['id' => 10, 'text' => 'text'];
        $db->method('fetchRow')->will($this->returnValue($data));

        $rep = new AdsRepository($db);

        $ad = $rep->getRow($data['id']);

        $this->assertInstanceOf(Ad::class, $ad);
        $this->assertEquals($data['id'], $ad->id);
        $this->assertEquals($data['text'], $ad->text);
    }

    /**
     * Проверка не успешной выборки записи.
     *
     */
    public function testGetRowNotOk()
    {
        $db = $this->createMock(DBInterface::class);
        $db->method('fetchRow')->will($this->returnValue(null));

        $rep = new AdsRepository($db);

        $id = 10;
        $this->assertNull($rep->getRow($id));
    }

    /**
     * Проверка успешной выборки записи для отображения.
     *
     */
    public function testGetRelevantOk()
    {
        $db = $this->createMock(DBInterface::class);
        $strategy = $this->createMock(RelevantStrategyInterface::class);

        $ad = $this->createMock(Ad::class);
        $strategy->method('selectOne')->will($this->returnValue($ad));

        $rep = new AdsRepository($db, $strategy);

        $relevant = $rep->getRelevant();
        $this->assertInstanceOf(Ad::class, $relevant);
        $this->assertEquals($ad, $relevant);
    }

    /**
     * Проверка не успешной выборки записи для отображения.
     *
     */
    public function testGetRelevantNotOk()
    {
        $db = $this->createMock(DBInterface::class);
        $strategy = $this->createMock(RelevantStrategyInterface::class);
        $strategy->method('selectOne')->will($this->returnValue(null));

        $rep = new AdsRepository($db, $strategy);

        $this->assertNull($rep->getRelevant());
    }

    /**
     * Проверка не успешной выборки записи для отображения?, из-за отсутствия стратегии.
     *
     */
    public function testGetRelevantNotOkNoStrategy()
    {
        $db = $this->createMock(DBInterface::class);
        $strategy = null;

        $rep = new AdsRepository($db, $strategy);

        $this->assertNull($rep->getRelevant());
    }

    /**
     * Проверка метода возврата стратегии, когда она не задана.
     *
     */
    public function testGetRelevantStrategyNoStrategy()
    {
        $db = $this->createMock(DBInterface::class);
        $strategy = null;

        $rep = new AdsRepository($db, $strategy);

        $this->assertEquals($strategy, $rep->getRelevantStrategy());
    }

    /**
     * Проверка метода возврата стратегии.
     *
     */
    public function testGetRelevantStrategy()
    {
        $db = $this->createMock(DBInterface::class);
        $strategy = $this->createMock(RelevantStrategyInterface::class);

        $rep = new AdsRepository($db, $strategy);

        $this->assertEquals($strategy, $rep->getRelevantStrategy());
    }

    /**
     * Проверка метода установки стратегии.
     */
    public function testSetRelevantStrategy()
    {
        $db = $this->createMock(DBInterface::class);
        $strategy = $this->createMock(RelevantStrategyInterface::class);

        $rep = new AdsRepository($db);
        $rep->setRelevantStrategy($strategy);

        $this->assertEquals($strategy, $rep->getRelevantStrategy());
    }
}
