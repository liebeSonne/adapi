<?php

namespace App;

use App\Common\Request\RequestInterface;
use App\Common\Response\ApiResponseInterface;
use App\Common\Route\RouterInterface;
use App\Common\Resources\ResourcesInterface;
use App\Common\Resources\Exception\ResourceException;
use App\Common\Route\Exception\RoutException;
use App\Api\Exception\ApiException;

class App
{
    /**
     * Режим работы приложения.
     *
     * @var string
     */
    protected $mode = '';

    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var ApiResponseInterface
     */
    protected $response;

    /**
     * @var ResourcesInterface
     */
    protected $resources;

    /**
     * @var RouterInterface
     */
    protected $router;

    public function __construct(
        RequestInterface $request,
        ApiResponseInterface $response,
        ResourcesInterface $resources,
        RouterInterface $router
    ) {
        $this->request = $request;
        $this->response = $response;
        $this->resources = $resources;
        $this->router = $router;
    }

    /**
     * @param string $mode
     */
    public function setMode(string $mode)
    {
        $this->mode = $mode;
    }

    /**
     * Запуск приложения.
     *
     * @throws \Exception
     */
    public function run(): void
    {
        try {
            $rout = $this->router->findRout($this->request);

            if (!$rout) {
                throw new RoutException('Invalid rout', 404);
            }

            $url = $this->request->getUrl();
            $rout->callResource($this->resources, $url);
        } catch (ApiException | ResourceException | RoutException $e) {
            $this->response->set($e->getCode(), $e->getMessage(), []);
            $this->response->display();
        } catch (\Exception $e) {
            if ($this->mode == 'dev') {
                throw $e;
            }
            $this->response->set(500, 'Server Error', []);
            $this->response->display();
        }
    }
}
