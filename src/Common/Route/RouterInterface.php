<?php

namespace App\Common\Route;

use App\Common\Request\RequestInterface;

/**
 * Интерфейс роутера, для управления роутами приложенрия.
 *
 */
interface RouterInterface
{
    /**
     * Регистрация роута.
     *
     * @param RoutInterface $rout
     * @return RouterInterface
     */
    public function reg(RoutInterface $rout): RouterInterface;

    /**
     * Поиск роута по параметрам запроса.
     *
     * @param RequestInterface $request
     * @return RoutInterface|NULL
     */
    public function findRout(RequestInterface $request): ?RoutInterface;
}
