<?php

namespace App\Repository;

use App\Model\Ad;
use App\Repository\Strategy\RelevantStrategyInterface;

/**
 * Интерфейс репозитория рекламных объявлений.
 *
 */
interface AdsRepositoryInterface
{
    /**
     * Добавление записи.
     *
     * @param Ad $item
     * @return int
     */
    public function add(Ad $item): int;

    /**
     * Редактирование записи.
     *
     * @param int $id
     * @param Ad $item
     * @return bool
     */
    public function edit(int $id, Ad $item): bool;

    /**
     * Действия при показе.
     *
     * @param Ad $ad
     * @return bool
     */
    public function onShow(Ad $ad): bool;

    /**
     * Возвращает одну запись.
     *
     * @param int $id
     * @return Ad|NULL
     */
    public function getRow(int $id): ?Ad;

    /**
     * Возвращает запись для показа.
     *
     * @return Ad|NULL
     */
    public function getRelevant(): ?Ad;

    /**
     * Задаёт стратегии поиска записей для показа.
     *
     * @param RelevantStrategyInterface $strategy
     */
    public function setRelevantStrategy(RelevantStrategyInterface $strategy = null);

    /**
     * Возврат стратегии поиска записей для показа.
     *
     * @return RelevantStrategyInterface|NULL
     */
    public function getRelevantStrategy(): ?RelevantStrategyInterface;
}
