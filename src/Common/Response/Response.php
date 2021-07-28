<?php

namespace App\Common\Response;

/**
 * Класс формирования ответа на запрос.
 *
 */
class Response implements ResponseInterface
{
    /**
     * Код статуса.
     *
     * @var int
     */
    protected $status = 200;

    /**
     * Текстстатуса.
     *
     * @var string
     */
    protected $statusText = 'Ok';

    /**
     * Заголовки.
     *
     * @var array
     */
    protected $headers = [];

    /**
     * Тело овтета.
     *
     * @var string
     */
    protected $body = '';

    /**
     * {@inheritDoc}
     * @see \App\Common\Response\ResponseInterface::addHeader()
     */
    public function addHeader(string $header): ResponseInterface
    {
        $this->headers[] = $header;
        return $this;
    }

    /**
     * {@inheritDoc}
     * @see \App\Common\Response\ResponseInterface::setStatus()
     */
    public function setStatus(int $status): ResponseInterface
    {
        $this->status = $status;
        return $this;
    }

    /**
     * {@inheritDoc}
     * @see \App\Common\Response\ResponseInterface::setStatusText()
     */
    public function setStatusText(string $statusText): ResponseInterface
    {
        $this->statusText = $statusText;
        return $this;
    }

    /**
     * {@inheritDoc}
     * @see \App\Common\Response\ResponseInterface::setBody()
     */
    public function setBody(string $body): ResponseInterface
    {
        $this->body = $body;
        return $this;
    }

    /**
     * {@inheritDoc}
     * @see \App\Common\Response\ResponseInterface::display()
     */
    public function display(): void
    {
        header("HTTP/1.1 " . $this->status . " " . $this->statusText);
        foreach ($this->headers as $header) {
            header($header);
        }

        echo $this->body;
    }
}
