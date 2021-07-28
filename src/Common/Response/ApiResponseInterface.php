<?php

namespace App\Common\Response;

/**
 * Интерфйейс класса формирующего ответ на запрос к API.
 *
 */
interface ApiResponseInterface
{
    /**
     * Устанавливает все параметры ответа одним методом.
     *
     * @param int $code
     * @param string $message
     * @param array $data
     * @return ApiResponseInterface
     */
    public function set(int $code, string $message = '', array $data = []): ApiResponseInterface;

    /**
     * Устанавливает код ответа.
     * @param int $code
     * @return ApiResponseInterface
     */
    public function setCode(int $code): ApiResponseInterface;

    /**
     * Устанавливает сообщение ответа.
     * @param string $message
     * @return ApiResponseInterface
     */
    public function setMessage(string $message): ApiResponseInterface;

    /**
     * Устанавливает массив данных дял ответа.
     * @param array $data
     * @return ApiResponseInterface
     */
    public function setData(array $data): ApiResponseInterface;

    /**
     * Запускает отобращение сформированного ответа.
     */
    public function display(): void;
}
