<?php

namespace App\Common\Response;

/**
 * Интерфейс класса реализующего формирование ответа на запрос.
 *
 */
interface ResponseInterface
{
    /**
     * Добавление заголовка.
     *
     * @param string $header
     * @return ResponseInterface
     */
    public function addHeader(string $header): ResponseInterface;

    /**
     * Указание кода состояния.
     * @param int $status
     * @return ResponseInterface
     */
    public function setStatus(int $status): ResponseInterface;

    /**
     * Указание текста состояния.
     * @param string $statusText
     * @return ResponseInterface
     */
    public function setStatusText(string $statusText): ResponseInterface;

    /**
     * Указание тела ответа.
     *
     * @param string $body
     * @return ResponseInterface
     */
    public function setBody(string $body): ResponseInterface;

    /**
     * Запускает отображение сформированного ответа.
     */
    public function display(): void;
}
