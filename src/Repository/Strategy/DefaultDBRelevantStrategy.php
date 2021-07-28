<?php

namespace App\Repository\Strategy;

use App\Model\Ad;
use App\Service\DBInterface;

/**
 * Класс реализующий стратегию по умолчанию для выборки рекламного объявления для показа.
 * Использует объект базы данных для выборки.
 *
 * Приоритеты выборки:
 * - с самой высокой стоимостью
 * - затем кого раньше добавили
 *
 */
class DefaultDBRelevantStrategy implements RelevantStrategyInterface
{
    /**
     * @var DBInterface
     */
    protected $db;

    /**
     * @param DBInterface $db
     */
    public function __construct(DBInterface $db)
    {
        $this->db = $db;
    }

    /**
     * {@inheritDoc}
     * @see \App\Repository\Strategy\RelevantStrategyInterface::selectOne()
     */
    public function selectOne(): ?Ad
    {
        $query = '
            SELECT
                *
            FROM
                `ads`
            WHERE
                `countShows` < `limit`
            ORDER BY
                `price` DESC, `id` ASC
            LIMIT 1
        ';
        $row = $this->db->fetchRow($query);
        return $row ? new Ad($row) : null;
    }
}
