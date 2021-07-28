<?php

namespace App\Common\Request;

/**
 * Интерфейс класса для работы с параметрами запроса.
 *
 */
interface RequestInterface
{
    /**
     * Возвращает метод http-запроса.
     *
     * @return string
     */
    public function getMethod(): string;

    /**
     * Возвращает параметры GET-запроса.
     *
     * @return array
     */
    public function getQueryParams(): array;

    /**
     * Возвращает параметры POST-запроса.
     *
     * @return array
     */
    public function getRequestParams(): array;

    /**
     * Возвращает URL строки запроса.
     *
     * @return string
     */
    public function getUrl(): string;
}
