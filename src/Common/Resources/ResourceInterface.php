<?php

namespace App\Common\Resources;

/**
 * Интерфейс ресурсов приложения.
 *
 */
interface ResourceInterface
{
    /**
     * Вызов метода ресурса.
     *
     * @param string $method
     * @param array $args
     */
    public function call(string $method, array $args = []): void;
}
