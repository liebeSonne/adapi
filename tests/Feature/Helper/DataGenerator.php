<?php

namespace Tests\Feature\Helper;

use Tests\Feature\Helper\ApiHelper;

/**
 * Класс с методами генерации тестовых данных.
 *
 */
class DataGenerator
{
    /**
     * @var ApiHelper
     */
    public $helper;

    public function __construct()
    {
        $this->helper = new ApiHelper();
    }

    /**
     * Возвращает массив code => message успешных ответов.
     *
     * @return array
     */
    public function getArrayCodeMessagesOk(): array
    {
        return [
            200 => 'Ok',
        ];
    }

    /**
     * Возвращает массив [code, message] ошибок.
     *
     * @return array
     */
    public function getArrayCodeMessagesError(): array
    {
        return [
            ['code' => 404, 'message' => 'Invalid rout'],
            ['code' => 404, 'message' => 'Invalid resource'],
            ['code' => 405, 'message' => 'Invalid resource method'],
            ['code' => 400, 'message' => 'Invalid argument'],
            ['code' => 400, 'message' => 'Invalid text'],
            ['code' => 400, 'message' => 'Invalid banner link'],
            ['code' => 400, 'message' => 'Invalid limit'],
            ['code' => 400, 'message' => 'Invalid price'],
            ['code' => 404, 'message' => 'Can not show'],
            ['code' => 500, 'message' => 'Can not add'],
            ['code' => 500, 'message' => 'Can not edit'],
            ['code' => 500, 'message' => 'Server Error'],
        ];
    }

    /**
     * Возвращает массив сообщений по коду (code => message[]).
     * На одном коде может быть несколько разных сообщений.
     *
     * @return array
     */
    public function getArrayMessagesByCodeError(): array
    {
        $items = $this->getArrayCodeMessagesError();
        $codes = [];
        foreach ($items as $item) {
            if (!isset($codes[$item['code']])) {
                $codes[$item['code']] = [];
            }
            $codes[$item['code']][] = $item['message'];
        }
        return $codes;
    }

    /**
     * Возвращает массив всех code => message.
     *
     * @return array
     */
    public function getArrayCodeMessagesAll(): array
    {
        $ok = $this->getArrayCodeMessagesOk();
        $error = $this->getArrayCodeMessagesError();
        return array_merge($ok, $error);
    }

    /**
     * Возвращает массив валидных данных стоимостей.
     *
     * @return array
     */
    public function generatePricesArrayValid(): array
    {
        return [0, 10, 300, 1000];
    }

    /**
     * Возвращает массив не валидных данных стоимостей.
     *
     * @return array
     */
    public function generatePricesArrayInvalid(): array
    {
        return [-1, -100, null, '', 'invalid', 'invalid10', '100invalid'];
    }

    /**
     * Возвращает массив валидных данных лимита.
     *
     * @return array
     */
    public function generateLimitArrayValid(): array
    {
        return [0, 10, 100, 1000];
    }

    /**
     * Возвращает массив не валидных данных лимита.
     *
     * @return array
     */
    public function generateLimitArrayInvalid(): array
    {
        return [-1, -100, null, '', 'invalid', 'invalid10', '100invalid'];
    }

    /**
     * Возвращает массив валидных данных текстов.
     *
     * @return array
     */
    public function generateTextArrayValid(): array
    {
        return [
            'Adtext',
            '123',
            123,
            'Adtextandnumbers123',
            'Ad with UPPER case and numbers 123 and spaces',
            'Ad with <b>html</b>',
        ];
    }

    /**
     * Возвращает массив не валидных данных текстов.
     *
     * @return array
     */
    public function generateTextArrayInvalid(): array
    {
        return [null, ''];
    }

    /**
     * Возвращает массив валидных данных баннеров.
     *
     * @return array
     */
    public function generateBannerArrayValid(): array
    {
        return [
            'http://banners.com/banner.png',
            'http://banners.com/banner.jpg',
            'http://banners.com/banner.jpeg',
            'https://banners.com/banner.png',
        ];
    }

    /**
     * Возвращает массив не валидных данных баннеров.
     *
     * @return array
     */
    public function generateBannerArrayInvalid(): array
    {
        return [
            null,
            10,
            '',
            'http://banners.com/invalid.pdf',
            'https://banners.com/banner.php',
            'http://invalid',
            'invalid',
            'invlid banner url',
        ];
    }

    /**
     * Возвращает массив валидных данных идентификаторов.
     *
     * @return array
     */
    public function generateIdArrayValid(): array
    {
        return [1, 2, 3];
    }

    /**
     * Возвращает массив не валидных данных идентификаторов.
     *
     * @return array
     */
    public function generateIdArrayInalid(): array
    {
        return [0, null, '', 'invalid'];
    }

    /**
     * Генерация набора параметров для запроса.
     * [text, banner, price, limit]
     *
     * @return array
     */
    public function generateRequestParamsOk(): array
    {
        // корректные данные
        $prices = $this->generatePricesArrayValid();
        $limits = $this->generateLimitArrayValid();
        $banners = $this->generateBannerArrayValid();
        $texts = $this->generateTextArrayValid();

        $params = [];
        foreach ($prices as $price) {
            foreach ($limits as $limit) {
                foreach ($banners as $banner) {
                    foreach ($texts as $text) {
                        $params[] = [
                            'text' => $text,
                            'banner' => $banner,
                            'price' => $price,
                            'limit' => $limit,
                        ];
                    }
                }
            }
        }

        return $params;
    }

    /**
     * Генерация набора параметров для запроса.
     * [text, banner, price, limit]
     *
     * @TODO - хорошо бы перемешивать с валидными данными, но чтоб хоть один параметр был не валидным
     *
     * @return array
     */
    public function generateRequestParamsErrorValid(): array
    {
        // корректные данные
        $prices = $this->generatePricesArrayInvalid();
        $limits = $this->generateLimitArrayInvalid();
        $banners = $this->generateBannerArrayInvalid();
        $texts = $this->generateTextArrayInvalid();

        $params = [];
        foreach ($prices as $price) {
            foreach ($limits as $limit) {
                foreach ($banners as $banner) {
                    foreach ($texts as $text) {
                        $params[] = [
                            'text' => $text,
                            'banner' => $banner,
                            'price' => $price,
                            'limit' => $limit,
                        ];
                    }
                }
            }
        }

        return $params;
    }

    /**
     * Генерация данных для успешных запросов добавления.
     *
     * @return array : [method, path, options][]
     */
    public function generateArrayAddDataRequestOk()
    {
        $params = $this->generateRequestParamsOk();

        $data = [];
        // add
        foreach ($params as $param) {
            $data[] = $this->helper->prepareAddRequestDataValid($param);
        }

        return $data;
    }

    /**
     * Генерация данных для не успешных но валидных запросов добавления.
     *
     * @return array : [method, path, options][]
     */
    public function generateArrayAddDataRequestErrorValid()
    {
        $params = $this->generateRequestParamsErrorValid();

        $data = [];
        // add
        foreach ($params as $param) {
            $data[] = $this->helper->prepareAddRequestDataValid($param);
        }

        return $data;
    }

    /**
     * Генерация данных для успешных запросов редактирования.
     *
     * @param array $ids
     * @return array : [method, path, options][]
     */
    public function generateArrayEditDataRequestOk(array $ids)
    {
        $params = $this->generateRequestParamsOk();

        $data = [];
        // edit
        foreach ($ids as $id) {
            foreach ($params as $param) {
                $data[] = $this->helper->prepareEditRequestDataValid($id, $param);
            }
        }

        return $data;
    }

    /**
     * Генерация данных для не успешных но валидных запросов редактирования.
     *
     * @param array $ids
     * @return array : [method, path, options][]
     */
    public function generateArrayEditDataRequestErrorValid(array $ids)
    {
        $params = $this->generateRequestParamsErrorValid();

        $data = [];
        // edit
        foreach ($ids as $id) {
            foreach ($params as $param) {
                $data[] = $this->helper->prepareEditRequestDataValid($id, $param);
            }
        }

        return $data;
    }

    /**
     * Генерация набора данных для успешных запросов.
     *
     * Результат: [method, path, options][]
     *
     * @return array
     */
    public function generateArrayAllDataRequestOk(): array
    {
        $data = [];

        // add
        $data = array_merge($data, $this->generateArrayAddDataRequestOk());

        // edit @TODO - для успешных запросов редактирования нужно точно знать какие id добавлены
        $ids = $this->generateIdArrayValid();
        $data = array_merge($data, $this->generateArrayEditDataRequestOk($ids));

        // relevant
        $data[] = $this->helper->prepareRelevantRequestDataValid();

        return $data;
    }

    /**
     * Генерация набора данных для не успешных но валидных запросов.
     *
     * Результат: [method, path, options][]
     *
     * @return array
     */
    public function generateArrayAllDataRequestErrorValid(): array
    {
        $data = [];

        // add
        $data = array_merge($data, $this->generateArrayAddDataRequestErrorValid());

        // edit @TODO - нужно знать существующие id чтоб были не только ошибки невозмоности найти запись
        $ids = $this->generateIdArrayValid();
        $data = array_merge($data, $this->generateArrayEditDataRequestErrorValid($ids));

        // relevant
        $data[] = $this->helper->prepareRelevantRequestDataInvalid();

        return $data;
    }
}
