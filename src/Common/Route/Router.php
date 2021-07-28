<?php

namespace App\Common\Route;

use App\Common\Request\RequestInterface;

/**
 * Класс роутера, управлябщего роутами.
 *
 */
class Router implements RouterInterface
{
    /**
     * @var RoutInterface[]
     */
    protected $routers = [];

    /**
     * {@inheritDoc}
     * @see \App\Common\Route\RouterInterface::reg()
     */
    public function reg(RoutInterface $rout): RouterInterface
    {
        $this->routers[] = $rout;
        return $this;
    }

    /**
     * {@inheritDoc}
     * @see \App\Common\Route\RouterInterface::findRout()
     */
    public function findRout(RequestInterface $request): ?RoutInterface
    {
        $method = $request->getMethod();
        $url = $request->getUrl();
        foreach ($this->routers as $rout) {
            if ($rout->isMatch($method, $url)) {
                return $rout;
            }
        }
        return null;
    }
}
