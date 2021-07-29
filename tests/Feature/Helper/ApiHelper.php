<?php

namespace Tests\Feature\Helper;

class ApiHelper
{
    /**
     * Возвращает структуру данных для запроса.
     *
     * @return array
     */
    public function prepareRequserData(string $method, string $path = '', array $params = []): array
    {
        $data = [
            'method' => $method,
            'path' => $path,
            'options' => [],
        ];
        if (!empty($params)) {
            $data['options']['form_params'] = $params;
        }
        return $data;
    }

     /**
     * Возвращает валидную структуру данных для успешного запроса добавления.
     *
     * @param array $params
     * @return array : [method, path, options]
     */
    public function prepareAddRequestDataValid(array $params): array
    {
        return [
            'method' => 'POST',
            'path' => '/ads',
            'options' => [
                'form_params' => $params,
            ],
        ];
    }

    /**
     * Возвращает валидную структуру данных для успешного запроса изменения.
     *
     * @param int $id
     * @param array $params
     * @return array : [method, path, options]
     */
    public function prepareEditRequestDataValid(int $id, array $params): array
    {
        return [
            'method' => 'POST',
            'path' => '/ads/' . $id,
            'options' => [
                'form_params' => $params,
            ],
        ];
    }

    /**
     * Возвращает валидную структуру данных для успешного запроса выборки для показа.
     *
     * @return array : [method, path, options]
     */
    public function prepareRelevantRequestDataValid(): array
    {
        return [
            'method' => 'GET',
            'path' => '/ads/relevant',
            'options' => [],
        ];
    }

    /**
     * Возвращает валидную структуру данных для не успешного запроса выборки для показа.
     *
     * @return array : [method, path, options]
     */
    public function prepareRelevantRequestDataInvalid(): array
    {
        return [
            'method' => 'POST',
            'path' => '/ads/relevant',
            'options' => [],
        ];
    }
}
