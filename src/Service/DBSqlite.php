<?php

namespace App\Service;

class DBSqlite implements DBInterface
{
    /**
     * @var array
     */
    protected $config = [
        'filename' => 'default.db.sqlite',
    ];

    /**
     * @var \SQLite3
     */
    protected $db;

    /**
     * $config:
     *  - filename
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->config = array_merge($this->config, $config);
    }

    /**
     * @return \SQLite3
     */
    protected function connect(): \SQLite3
    {
        if (!$this->db) {
            $this->db = new \SQLite3($this->config['filename']);
        }
        return $this->db;
    }

    /**
     * @param string $query
     * @param array $params
     * @return \SQLite3Result|NULL
     */
    protected function queryParams(string $query, array $params = []): ?\SQLite3Result
    {
        $statement = $this->connect()->prepare($query);
        foreach ($params as $key => $value) {
            $statement->bindValue($key, $value);
        }
        return $statement->execute();
    }

    public function query(string $query, array $params = []): bool
    {
        if (!empty($params)) {
            return (bool) $this->queryParams($query, $params);
        } else {
            return (bool) $this->connect()->exec($query);
        }
    }

    public function insert(string $query, array $params = []): ?int
    {
        $result = null;
        if (!empty($params)) {
            $result = $this->queryParams($query, $params);
        } else {
            $result = $this->connect()->query($query);
        }
        return $result ? $this->connect()->lastInsertRowID() : null;
    }

    public function fetchRow(string $query, array $params = []): ?array
    {
        $result = null;
        if (!empty($params)) {
            $result = $this->queryParams($query, $params);
        } else {
            $result = $this->connect()->query($query);
        }
        $row = $result ? $result->fetchArray(SQLITE3_ASSOC) : null;
        return $row ? $row : null;
    }

    public function fetchArray(string $query, array $params = [], string $field = ''): array
    {
        $rows = [];
        $result = null;
        if (!empty($params)) {
            $result = $this->queryParams($query, $params);
        } else {
            $result = $this->connect()->query($query);
        }
        if ($result) {
            while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                if ($field) {
                    $rows[$row[$field]] = $row;
                } else {
                    $rows[] = $row;
                }
            }
        }
        return $rows;
    }
}
