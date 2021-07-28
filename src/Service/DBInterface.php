<?php

namespace App\Service;

/**
 * Интерфей классов для работы с базой данных.
 */
interface DBInterface
{
    /**
     * Выполнение запроса.
     *
     * @param string $query
     * @param array $params
     * @return bool
     */
    public function query(string $query, array $params = []): bool;

    /**
     * Запрос добавления с возвратом идентификатора добавленной записи.
     *
     * @param string $query
     * @param array $params
     * @return int|NULL
     */
    public function insert(string $query, array $params = []): ?int;

    /**
     * Выполнение запроса выборки одной записи.
     *
     * @param string $query
     * @param array $params
     * @return array|NULL
     */
    public function fetchRow(string $query, array $params = []): ?array;

    /**
     * Выполнение запроса выборки масива записей.
     *
     * @param string $query
     * @param array $params
     * @return array
     */
    public function fetchArray(string $query, array $params = []): array;
}
