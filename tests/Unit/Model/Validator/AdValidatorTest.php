<?php

namespace Tests\Unit\Model\Validator;

use PHPUnit\Framework\TestCase;
use App\Model\Validator\AdValidator;
use App\Model\Ad;

class AdValidatorTest extends TestCase
{
    /**
     * Возвращает массимы данных для Ad и результаты ожидаемой проверки.
     *
     * первый аргумент - массив данных Ad: id, text, banner, price, limit
     * второй аргумент - массив значений ожидаемых результатов: valid, errors
     *
     * @return array
     */
    public function dataProviderAdData()
    {
        return [
            [
                [
                    'id' => 1,
                    'text' => 'text',
                    'banner' => 'http://banner.com/img.png',
                    'price' => 100,
                    'limit' => 100,
                ],
                [
                    'valid' => true,
                    'errors' => [],
                ]
            ],
            [
                [
                    'id' => 2,
                    'text' => '',
                    'banner' => 'invalid',
                    'price' => -1,
                    'limit' => -1,
                ],
                [
                    'valid' => false,
                    'errors' => [
                        'Invalid text',
                        'Invalid banner link',
                        'Invalid limit',
                        'Invalid price',
                    ],
                ]
            ],
            [
                [
                    'id' => 3,
                    'text' => 'text',
                    'banner' => 'http://banner.com/img.png',
                    'price' => -100,
                    'limit' => 100,
                ],
                [
                    'valid' => false,
                    'errors' => [
                        'Invalid price',
                    ],
                ]
            ],
            [
                [
                    'id' => 4,
                    'text' => 'text',
                    'banner' => '',
                    'price' => 100,
                    'limit' => 100,
                ],
                [
                    'valid' => false,
                    'errors' => [
                        'Invalid banner link',
                    ],
                ]
            ],
        ];
    }

    /**
     * Проверка методов валидатора.
     *
     * @dataProvider dataProviderAdData
     */
    public function testAds($data, $result)
    {
        $ad = new Ad($data);
        $validator = new AdValidator($ad);

        $this->assertEquals($result['valid'], $validator->isValid());
        $this->assertEquals($result['errors'], $validator->getErrors());
        if (empty($result['errors'])) {
            $this->assertEquals('', $validator->getFirstError());
        } else {
            $this->assertContains($validator->getFirstError(), $result['errors']);
        }
    }
}
