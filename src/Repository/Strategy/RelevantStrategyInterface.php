<?php

namespace App\Repository\Strategy;

use App\Model\Ad;

/**
 * Интерфейс класса реализующего стратегию выборки объявления для показа.
 *
 */
interface RelevantStrategyInterface
{
    /**
     * Выборка одной записи рекламного объявления..
     * @return Ad|NULL
     */
    public function selectOne(): ?Ad;
}
