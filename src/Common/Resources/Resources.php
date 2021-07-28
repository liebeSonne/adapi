<?php

namespace App\Common\Resources;

/**
 * Класс реализующий управление ресурсами приложения.
 *
 */
class Resources implements ResourcesInterface
{
    /**
     * @var ResourceInterface[]
     */
    protected $resources = [];

    /**
     * {@inheritDoc}
     * @see \App\Common\Resources\ResourcesInterface::reg()
     */
    public function reg(string $class, ResourceInterface $resource): ResourcesInterface
    {
        $this->resources[$class] = $resource;
        return $this;
    }

    /**
     * {@inheritDoc}
     * @see \App\Common\Resources\ResourcesInterface::getResource()
     */
    public function getResource(string $class): ?ResourceInterface
    {
        return $this->resources[$class] ?? null;
    }
}
