<?php

namespace App\Common\Route;

use App\Common\Resources\Exception\ResourceException;
use App\Common\Resources\ResourcesInterface;

/**
 * Класс роута, соединяющего URL и метод запроса с методом ресурса приложения.
 *
 */
class Rout implements RoutInterface
{
    /**
     * Метод запроса.
     *
     * @var string
     */
    protected $method = '';

    /**
     * Шаблон резулярного выражения.
     *
     * @var string
     */
    protected $pattern = '';

    /**
     * Класс ресурса.
     *
     * @var string
     */
    protected $class = '';

    /**
     * Метод ресурса.
     *
     * @var string
     */
    protected $action = '';

    /**
     * @param string $method
     * @param string $pattern
     * @param string $class
     * @param string $action
     */
    public function __construct(string $method, string $pattern, string $class, string $action)
    {
        $this->method = $method;
        $this->pattern = $pattern;
        $this->class = $class;
        $this->action = $action;
    }

    /**
     * {@inheritDoc}
     * @see \App\Common\Route\RoutInterface::isMatch()
     */
    public function isMatch(string $method, string $url): bool
    {
        if ($this->method != $method) {
            return false;
        }

        if (preg_match($this->pattern, $url)) {
            return true;
        }
        return false;
    }

    /**
     * {@inheritDoc}
     * @see \App\Common\Route\RoutInterface::getArguments()
     */
    public function getArguments(string $url): array
    {
        $matches = [];
        if (preg_match($this->pattern, $url, $matches)) {
            return $matches;
        }
        return [];
    }

    /**
     * {@inheritDoc}
     * @see \App\Common\Route\RoutInterface::callResource()
     * @throws ResourceException
     */
    public function callResource(ResourcesInterface $resources, string $url)
    {
        $resource = $resources->getResource($this->class);

        if (!$resource) {
            throw new ResourceException('Invalid resource', 404);
        }

        $args = $this->getArguments($url);
        $resource->call($this->action, $args);
    }
}
