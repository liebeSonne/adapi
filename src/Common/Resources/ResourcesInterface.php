<?php

namespace App\Common\Resources;

/**
 * Интерфейс ресурсов приложения.
 *
 */
interface ResourcesInterface
{
    /**
     * Регистрация ресурса.
     * Привязка класса ресурса к объекту ресурса.
     *
     * @param string $class
     * @param ResourceInterface $resource
     * @return ResourcesInterface
     */
    public function reg(string $class, ResourceInterface $resource): ResourcesInterface;

    /**
     * @param string $class
     * @return ResourceInterface|NULL
     */
    public function getResource(string $class): ?ResourceInterface;
}
