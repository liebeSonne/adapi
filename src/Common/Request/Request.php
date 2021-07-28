<?php

namespace App\Common\Request;

/**
 * Класс для работы с параметрами запроса.
 *
 */
class Request implements RequestInterface
{
    /**
     * {@inheritDoc}
     * @see \App\Common\Request\RequestInterface::getMethod()
     */
    public function getMethod(): string
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    /**
     * {@inheritDoc}
     * @see \App\Common\Request\RequestInterface::getQueryParams()
     */
    public function getQueryParams(): array
    {
        return $_GET;
    }

    /**
     * {@inheritDoc}
     * @see \App\Common\Request\RequestInterface::getRequestParams()
     */
    public function getRequestParams(): array
    {
        return $_POST;
    }

    /**
     * {@inheritDoc}
     * @see \App\Common\Request\RequestInterface::getUrl()
     */
    public function getUrl(): string
    {
        return urldecode(trim($_SERVER['REQUEST_URI'], '/'));
    }
}
