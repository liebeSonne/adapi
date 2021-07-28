<?php

namespace App\Model;

/**
 * Модель с данными рекламного объявления.
 *
 */
class Ad
{
    /**
     * Идентификатор
     *
     * @var number
     */
    public $id;

    /**
     * Заголовок объявления
     *
     * @var string
     */
    public $text = '';

    /**
     * Стоимость одного показа
     *
     * @var number
     */
    public $price = 0;

    /**
     * Лимит показов
     *
     * @var number
     */
    public $limit = 0;

    /**
     * Ссылка на картинку
     *
     * @var string
     */
    public $banner = '';

    /**
     * Количество показов.
     *
     * @var number
     */
    public $countShows = 0;

    /**
     * Время крайнего показа рекламы.
     * @var integer
     */
    public $timeLastShow = 0;

    /**
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        if (array_key_exists('id', $data)) {
            $this->id = (int) $data['id'];
        }
        if (array_key_exists('text', $data)) {
            $this->text = (string) $data['text'];
        }
        if (array_key_exists('limit', $data)) {
            $this->limit = (int) $data['limit'];
        }
        if (array_key_exists('price', $data)) {
            $this->price = (int) $data['price'];
        }
        if (array_key_exists('banner', $data)) {
            $this->banner = (string) $data['banner'];
        }
        if (array_key_exists('countShows', $data)) {
            $this->countShows = (int) $data['countShows'];
        }
        if (array_key_exists('timeLastShow', $data)) {
            $this->timeLastShow = (int) $data['timeLastShow'];
        }
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'text' => $this->text,
            'price' => $this->price,
            'limit' => $this->limit,
            'banner' => $this->banner,
            'countShows' => $this->countShows,
            'timeLastShow' => $this->timeLastShow,
        ];
    }
}
