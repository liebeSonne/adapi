<?php

namespace App\Model\Validator;

use App\Model\Ad;

/**
 * Валидатор данных модели рекламного объявления.
 *
 */
class AdValidator implements ValidatorInterface
{
    /**
     * @var Ad
     */
    protected $item;

    /**
     * @var array
     */
    protected $errors = [];

    /**
     * @param Ad $item
     */
    public function __construct(Ad $item)
    {
        $this->item = $item;
        $this->errors = $this->checkErrors();
    }

    /**
     * Проверяет данных из массива указанных значений.
     * Используется для возможности проверять только некоторые значения..
     *
     * @param array $data
     * @return string[]
     */
    protected function checkDataErrors(array $data)
    {
        $errors = [];

        if (array_key_exists('text', $data)) {
            if (empty($data['text'])) {
                $errors[] = 'Invalid text';
            }
        }
        if (array_key_exists('banner', $data)) {
            if (!$this->isBannerLink($data['banner'])) {
                $errors[] = 'Invalid banner link';
            }
        }

        if (array_key_exists('limit', $data)) {
            if ($data['limit'] < 0) {
                $errors[] = 'Invalid imit';
            }
        }

        if (array_key_exists('price', $data)) {
            if ($data['price'] < 0) {
                $errors[] = 'Invalid price';
            }
        }

        return $errors;
    }

    /**
     * Запуск проверок на ошибки.
     *
     * @return array
     */
    protected function checkErrors(): array
    {
        $data = $this->item->toArray();

        return $this->checkDataErrors($data);
    }

    /**
     * Проверка поля banner.
     *
     * @param string $link
     * @return boolean
     */
    protected function isBannerLink($link)
    {
        if (empty($link)) {
            return false;
        }
        if (preg_match('/^https?:\/\/\S+(?:jpg|jpeg|png)$/', (string) $link)) {
            return true;
        }
        return false;
    }

    /**
     * {@inheritDoc}
     * @see \App\Model\Validator\ValidatorInterface::isValid()
     */
    public function isValid(): bool
    {
        return empty($this->errors);
    }

    /**
     * {@inheritDoc}
     * @see \App\Model\Validator\ValidatorInterface::getErrors()
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * {@inheritDoc}
     * @see \App\Model\Validator\ValidatorInterface::getFirstError()
     */
    public function getFirstError(): string
    {
        return $this->errors[0] ?? '';
    }
}
