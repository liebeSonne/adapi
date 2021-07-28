<?php

namespace App\Common\Route;

use App\Common\Resources\ResourcesInterface;

/**
 * Интерфейс роута.
 * Соединяющего URL и метод запроса с методами ресурса который его обрабатывает.
 *
 */
interface RoutInterface
{
    /**
     * Проверка соответствия роута методу и URL запроса.
     *
     * @param string $method
     * @param string $url
     * @return bool
     */
    public function isMatch(string $method, string $url): bool;

    /**
     * Возвращает аргументы запроса из URL.
     *
     * @param string $url
     * @return array
     */
    public function getArguments(string $url): array;

    /**
     * Вызывает метод ресурса обрабатывающего роут, через объект управления ресурсами.
     *
     * @param ResourcesInterface $resources
     * @param string $url
     */
    public function callResource(ResourcesInterface $resources, string $url);
}
