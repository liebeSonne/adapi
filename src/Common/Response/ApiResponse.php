<?php

namespace App\Common\Response;

/**
 * Абстрактный класс для формарования ответов на запросы к API.
 * Без реализации метода отображения результата.
 *
 */
abstract class ApiResponse implements ApiResponseInterface
{
    /**
     * Код.
     *
     * @var integer
     */
    protected $code = 200;

    /**
     * Сообщение.
     *
     * @var string
     */
    protected $message = 'Ok';

    /**
     * Массив данных.
     *
     * @var array
     */
    protected $data = [];

    /**
     * {@inheritDoc}
     * @see \App\Common\Response\ApiResponseInterface::set()
     */
    public function set(int $code, string $message = '', array $data = []): ApiResponseInterface
    {
        return $this->setCode($code)->setMessage($message)->setData($data);
    }

    /**
     * {@inheritDoc}
     * @see \App\Common\Response\ApiResponseInterface::setCode()
     */
    public function setCode(int $code): ApiResponseInterface
    {
        $this->code = $code;
        return $this;
    }

    /**
     * {@inheritDoc}
     * @see \App\Common\Response\ApiResponseInterface::setMessage()
     */
    public function setMessage(string $message): ApiResponseInterface
    {
        $this->message = $message;
        return $this;
    }

    /**
     * {@inheritDoc}
     * @see \App\Common\Response\ApiResponseInterface::setData()
     */
    public function setData(array $data): ApiResponseInterface
    {
        $this->data = $data;
        return $this;
    }

    /**
     * {@inheritDoc}
     * @see \App\Common\Response\ApiResponseInterface::display()
     */
    abstract public function display(): void;
}
