<?php

namespace App\Repository;

use App\Model\Ad;
use App\Service\DBInterface;
use App\Repository\Strategy\RelevantStrategyInterface;

/**
 * Репозиторий рекламных объявлений.
 * Использующий в качестве хранилища базу данных.
 *
 */
class AdsRepository implements AdsRepositoryInterface
{
    /**
     * @var RelevantStrategyInterface
     */
    protected $relevantStrategy;

    /**
     * @var DBInterface
     */
    protected $db;

    /**
     * @param DBInterface $db
     * @param RelevantStrategyInterface $relevantStrategy
     */
    public function __construct(
        DBInterface $db,
        RelevantStrategyInterface $relevantStrategy = null
    ) {
        $this->db = $db;
        $this->relevantStrategy = $relevantStrategy;
    }

    /**
     * {@inheritDoc}
     * @see \App\Repository\AdsRepositoryInterface::add()
     */
    public function add(Ad $item): int
    {
        $query = '
            INSERT INTO
                `ads`
                (`text`, `price`, `limit`, `banner`)
            VALUES
                (:text, :price, :limit, :banner)
        ';
        $params = [
            ':text' => (string) $item->text,
            ':price' => (int) $item->price,
            ':limit' => (int) $item->limit,
            ':banner' => (string) $item->banner,
        ];
        return (int) $this->db->insert($query, $params);
    }

    /**
     * {@inheritDoc}
     * @see \App\Repository\AdsRepositoryInterface::edit()
     */
    public function edit(int $id, Ad $item): bool
    {
        $query = '
            UPDATE
                `ads`
            SET
                `text` = :text,
                `price` = :price,
                `limit` = :limit,
                `banner` = :banner
            WHERE
                `id` = :id
        ';
        $params = [
            ':id' => (int) $id,
            ':text' => (string) $item->text,
            ':price' => (int) $item->price,
            ':limit' => (int) $item->limit,
            ':banner' => (string) $item->banner,
        ];
        return (bool) $this->db->query($query, $params);
    }

    /**
     * {@inheritDoc}
     * @see \App\Repository\AdsRepositoryInterface::onShow()
     */
    public function onShow(Ad $ad): bool
    {
        $query = '
            UPDATE
                `ads`
            SET
                `countShows` = `countShows` + 1,
                `timeLastShow` = CURRENT_TIMESTAMP
            WHERE
                `id` = :id
            LIMIT 1
        ';
        $params = [
            ':id' => (int) $ad->id,
        ];
        return (bool) $this->db->query($query, $params);
    }

    /**
     * {@inheritDoc}
     * @see \App\Repository\AdsRepositoryInterface::getRow()
     */
    public function getRow(int $id): ?Ad
    {
        $query = '
            SELECT
                *
            FROM
                `ads`
            WHERE
                `id` = :id
            LIMIT 1
        ';
        $params = [
            ':id' => (int) $id
        ];
        $row = $this->db->fetchRow($query, $params);
        return $row ? new Ad($row) : null;
    }

    /**
     * {@inheritDoc}
     * @see \App\Repository\AdsRepositoryInterface::getRelevant()
     */
    public function getRelevant(): ?Ad
    {
        if ($this->relevantStrategy) {
            return $this->relevantStrategy->selectOne();
        }
        return null;
    }

    /**
     * {@inheritDoc}
     * @see \App\Repository\AdsRepositoryInterface::setRelevantStrategy()
     */
    public function setRelevantStrategy(RelevantStrategyInterface $strategy = null)
    {
        $this->relevantStrategy = $strategy;
    }

    /**
     * {@inheritDoc}
     * @see \App\Repository\AdsRepositoryInterface::getRelevantStrategy()
     */
    public function getRelevantStrategy(): ?RelevantStrategyInterface
    {
        return $this->relevantStrategy;
    }
}
