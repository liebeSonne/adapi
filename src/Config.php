<?php

namespace App;

/**
 * Настройки.
 *
 */
class Config
{
    /**
     * @var array
     */
    protected $data = [];

    /**
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    /**
     * @return string
     */
    public function getMode(): string
    {
        return $this->data['mode'] ?? '';
    }

    /**
     * @return array
     */
    public function getDatabse(): string
    {
        return $this->data['db']['filename'] ?? [];
    }

    /**
     * @return array
     */
    public function getDatabaseSchemas(): array
    {
        return $this->data['db']['schema'] ?? [];
    }

    /**
     * @return string
     */
    public function getRelevantStrategy(): string
    {
        return $this->data['relevant']['strategy'] ?? '';
    }
}
