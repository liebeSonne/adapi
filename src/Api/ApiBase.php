<?php

namespace App\Api;

use App\Common\Resources\ResourceInterface;
use App\Common\Request\RequestInterface;
use App\Common\Response\ApiResponseInterface;
use App\Api\Exception\ApiException;

/**
 * Базовый класс API-ресурса приложения.
 *
 */
class ApiBase implements ResourceInterface
{
    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var ApiResponseInterface
     */
    protected $response;

    /**
     * @param RequestInterface $request
     * @param ApiResponseInterface $response
     */
    public function __construct(
        RequestInterface $request,
        ApiResponseInterface $response
    ) {
        $this->request = $request;
        $this->response = $response;
    }

    /**
     * {@inheritDoc}
     * @see \App\Common\Resources\ResourceInterface::call()
     * @throws ApiException
     */
    public function call(string $method, array $args = []): void
    {
        if (method_exists($this, $method)) {
            $this->{$method}($args);
            return;
        }

        throw new ApiException('Invalid resource method', 405);
    }
}
