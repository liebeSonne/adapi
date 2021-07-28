<?php

namespace App\Repository\Strategy;

use App\Model\Ad;
use App\Service\DBInterface;

/**
 * Класс реализующий продвинутую стратегию для выборки рекламного объявления для показа.
 * Использует объект базы данных для выборки.
 *
 * Приоритеты выборки:
 *
 * - снижаем приоритет недавно показанных
 * - приоритетней те у кого оставшееся количество на стоимость больше
 * - затем по уменьшению процента оставшегося количества
 * - затем по уменьшению стоимости
 * - затем те, что раньше добавились
 *
 */
class AdvancedDBRelevantStrategy implements RelevantStrategyInterface
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
                (CASE WHEN (CURRENT_TIMESTAMP - `timeLastShow`) < 60  THEN 0 ELSE 1 END) DESC,
                (`price` * ( `limit` - `countShows`)) DESC,
                (CASE WHEN `limit` != 0 THEN (`limit` - `countShows`) / `limit` ELSE 0 END) DESC,
                `price` DESC,
                `id` DESC
            LIMIT 1
        ';
        $row = $this->db->fetchRow($query);
        return $row ? new Ad($row) : null;
    }
}
