<?php

namespace Tests\Unit\Model;

use PHPUnit\Framework\TestCase;
use App\Model\Ad;

class AdTest extends TestCase
{
    /**
     * Провайдер данных с массивами параметров для класса Ad.
     *
     * @return array
     */
    public function dataProviderAdData(): array
    {
        return [
            [
                [
                    'id' => 1,
                    'text' => 'Ad text',
                    'banner' => 'http://images.com/img.png',
                    'price' => 100,
                    'limit' => 1000,
                    'countShows' => 120,
                    'timeLastShow' => 1627650000, // 2021-07-30 13:00:00
                ]
            ],
            [
                [
                    'id' => null,
                    'text' => '',
                    'banner' => '',
                    'price' => 0,
                    'limit' => 0,
                    'countShows' => 0,
                    'timeLastShow' => 0,
                ]
            ],
        ];
    }

    /**
     * Проверка конструктора с массивом данных.
     *
     * @dataProvider dataProviderAdData
     */
    public function testConstructor($data)
    {
        $ad = new Ad($data);
        $this->assertEquals($data['id'], $ad->id);
        $this->assertEquals($data['text'], $ad->text);
        $this->assertEquals($data['banner'], $ad->banner);
        $this->assertEquals($data['price'], $ad->price);
        $this->assertEquals($data['limit'], $ad->limit);
        $this->assertEquals($data['countShows'], $ad->countShows);
        $this->assertEquals($data['timeLastShow'], $ad->timeLastShow);
    }

    /**
     * Проверка метода toArray, после контсруктора с массивом данных.
     *
     * @dataProvider dataProviderAdData
     */
    public function testConstructorToArray($data)
    {
        $ad = new Ad($data);
        $array = $ad->toArray();

        $this->assertArrayHasKey('id', $array);
        $this->assertArrayHasKey('text', $array);
        $this->assertArrayHasKey('banner', $array);
        $this->assertArrayHasKey('price', $array);
        $this->assertArrayHasKey('limit', $array);
        $this->assertArrayHasKey('countShows', $array);
        $this->assertArrayHasKey('timeLastShow', $array);

        $this->assertEquals($ad->id, $array['id']);
        $this->assertEquals($ad->text, $array['text']);
        $this->assertEquals($ad->banner, $array['banner']);
        $this->assertEquals($ad->price, $array['price']);
        $this->assertEquals($ad->limit, $array['limit']);
        $this->assertEquals($ad->countShows, $array['countShows']);
        $this->assertEquals($ad->timeLastShow, $array['timeLastShow']);
    }

    /**
     * Провайдер данных с массивами параметров для класса Ad, у которых не тот тип данных.
     * Ожидается что через контруктор данные будут приведены в нужный тип.
     *
     * @return array
     */
    public function dataProviderAdDataNoType(): array
    {
        return [
            [
                [
                    'id' => '1',
                    'text' => 123,
                    'banner' => 333,
                    'price' => '100',
                    'limit' => '1000',
                    'countShows' => '120',
                    'timeLastShow' => '1627650000', // 2021-07-30 13:00:00
                ]
            ],
            [
                [
                    'id' => '',
                    'text' => 0,
                    'banner' => 0,
                    'price' => '',
                    'limit' => '',
                    'countShows' => '',
                    'timeLastShow' => '',
                ]
            ],
        ];
    }

    /**
     * Проверка конструктора с массивом данных с приведением типов.
     *
     * @dataProvider dataProviderAdDataNoType
     */
    public function testConstructorToType($data)
    {
        $ad = new Ad($data);
        $this->assertEquals((int) $data['id'], $ad->id);
        $this->assertEquals((string) $data['text'], $ad->text);
        $this->assertEquals((string) $data['banner'], $ad->banner);
        $this->assertEquals((int) $data['price'], $ad->price);
        $this->assertEquals((int) $data['limit'], $ad->limit);
        $this->assertEquals((int) $data['countShows'], $ad->countShows);
        $this->assertEquals((int) $data['timeLastShow'], $ad->timeLastShow);
    }
}
