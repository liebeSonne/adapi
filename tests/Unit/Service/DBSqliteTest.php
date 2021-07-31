<?php

namespace Tests\Unit\Service;

use App\Service\DBInterface;
use App\Service\DBSqlite;
use PHPUnit\Framework\TestCase;

/**
 * Класс тестов объекта для работы с БД.
 *
 */
class DBSqliteTest extends TestCase
{
    /**
     * Проверка конструктора.
     *
     */
    public function testConstruct()
    {
        $config = ['filename' => 'test.db.sqlite'];
        $db = new DBSqlite($config);

        $this->assertInstanceOf(DBSqlite::class, $db);

        return $db;
    }

    /**
     * Возввращает параметры запросов и их результаты.
     *
     * первый аргемент массив: query, result, value
     *
     * @return array
     */
    public function dataProviderQuery(): array
    {
        return [
            [
                [
                    'query' => 'SELECT 1',
                    'params' => [],
                    'result' => true,
                    'value' => 1,
                ]
            ],
            [
                [
                    'query' => 'SELECT 1',
                    'params' => [':id' => 1],
                    'result' => true,
                    'value' => 1,
                ]
            ],
            [
                [
                    'query' => 'SELECT 1 WHERE 1=0',
                    'params' => [],
                    'result' => true,
                    'value' => null,
                ]
            ],
            [
                [
                    'query' => 'SELECT 1 WHERE 1=0',
                    'params' => [':id' => 1],
                    'result' => true,
                    'value' => null,
                ]
            ],
            [
                [
                    'query' => 'SELECT 1 WHERE :id = :id2',
                    'params' => [':id' => 1, ':id2' => 0],
                    'result' => true,
                    'value' => null,
                ]
            ],
            [
                [
                    'query' => 'SELECT 1 WHERE :id = :id2',
                    'params' => [':id' => 1, ':id2' => 1],
                    'result' => true,
                    'value' => 1,
                ]
            ],
        ];
    }

    /**
     * Проверка метода выполнения запроса.
     *
     * @dataProvider dataProviderQuery
     * @depends testConstruct
     */
    public function testQuery($data, $db)
    {
        $result = $db->query($data['query'], $data['params']);
        $this->assertEquals($data['result'], $result);
    }

    /**
     * Проверка метода выполнения запроса c исключением.
     * @TODO - пойдет так, с перехватом ошибки или нет? как сделать лучше?
     *
     * @depends testConstruct
     */
    public function testQueryException($db)
    {
        try {
            $query = 'NOT VALIG QUERY';
            $result = $db->query($query);
            $this->assertEquals(false, $result);
        } catch (\Exception $e) {
            $this->assertInstanceOf(\Exception::class, $e);
            $this->assertMatchesRegularExpression('/.*SQLite3.*/', $e->getMessage());
        }
    }

    /**
     * Возвращает данные для запросов insert.
     *
     * первый парамер - массив: query, params, isNull
     *
     * @return array
     */
    public function dataProviderInsert(): array
    {
        return [
            [
                [
                    'query' => 'INSERT INTO `test_table` (`name`) VALUES ("name1")',
                    'params' => [],
                    'isNull' => false,
                ],
            ],
            [
                [
                    'query' => 'INSERT INTO `test_table` (`name`) VALUES (:name)',
                    'params' => [':name' => "name2"],
                    'isNull' => false,
                ],
            ],
        ];
    }

    /**
     * Добавление тестовой табилцы.
     *
     * @return DBInterface
     */
    public function testCreateTableDB()
    {
        $config = ['filename' => 'test.db.sqlite'];
        $querySchema = 'CREATE TABLE IF NOT EXISTS `test_table` (
            `id` INTEGER PRIMARY KEY AUTOINCREMENT,
            `name` TEXT DEFAULT ""
        );';
        $db = new DBSqlite($config);
        $result = $db->query($querySchema);

        $this->assertInstanceOf(DBSqlite::class, $db);
        $this->assertTrue($result);

        return $db;
    }

    /**
     * Проверка вставки.
     *
     * @dataProvider dataProviderInsert
     * @depends testCreateTableDB
     */
    public function testInsert($data, $db)
    {
        $result = $db->insert($data['query'], $data['params']);
        if ($data['isNull']) {
            $this->assertNull($result);
        } else {
            $this->assertIsInt($result);
        }

        return $result;
    }

    /**
     * Возвращает данные для проверки выборки записей.
     *
     * @return array
     */
    public function dataProviderFetchRow(): array
    {
        return [
            [
                [
                    'insert' => 'INSERT INTO `test_table` (`name`) VALUES ("name")',
                    'select' => 'SELECT * FROM `test_table` WHERE `id` = :id',
                    'isParamsId' => true,
                    'data' => ['name' => "name"],
                    'hasRow' => true,
                ],
            ],
            [
                [
                    'insert' => 'INSERT INTO `test_table` (`name`) VALUES ("name2")',
                    'select' => 'SELECT * FROM `test_table` WHERE `id` = %id%',
                    'isParamsId' => false,
                    'data' => ['name' => "name2"],
                    'hasRow' => true,
                ],
            ],
            [
                [
                    'insert' => 'INSERT INTO `test_table` (`name`) VALUES ("name4")',
                    'select' => 'SELECT * FROM `test_table` WHERE 1 = 0',
                    'isParamsId' => false,
                    'data' => [],
                    'hasRow' => false,
                ],
            ],
        ];
    }

    /**
     * Проверка выборки одной записи.
     *
     * @dataProvider dataProviderFetchRow
     * @depends testCreateTableDB
     */
    public function testFetchRow($data, $db)
    {
        // чтоб наверняка была запись
        $id = $db->insert($data['insert']);

        $this->assertIsInt($id);

        $query = $data['select'];
        $params = [];

        if ($data['isParamsId']) {
            // для запроса с подстановкой параметров
            $params[':id'] = $id;
        } else {
            // для запрос без подстановки парамтеров
            $query = str_replace('%id%', $id, $query);
        }

        $row = $db->fetchRow($query, $params);

        if ($data['hasRow']) {
            $this->assertIsArray($row);
            $this->assertArrayHasKey('id', $row);
            $this->assertArrayHasKey('name', $row);
            $this->assertEquals($id, $row['id']);
            $this->assertEquals($data['data']['name'], $row['name']);
        } else {
            $this->assertNull($row);
        }
    }

    /**
     * Проверка выборки одной записи, когда есть хоятыб одна в базе.
     *
     * @depends testCreateTableDB
     * @depends testInsert
     */
    public function testFetchRowAfterEny($db)
    {
        $query = 'SELECT * FROM `test_table` LIMIT 1';
        $row = $db->fetchRow($query);

        $this->assertIsArray($row);
        $this->assertArrayHasKey('id', $row);
        $this->assertArrayHasKey('name', $row);
    }

    /**
     * Проверка выборки массива записей, когда есть хоятбы одна а базе.
     *
     * @depends testCreateTableDB
     * @depends testInsert
     */
    public function testFetchArray($db)
    {
        $query = 'SELECT * FROM `test_table`';
        $params = [];
        $field = '';

        $rows = $db->fetchArray($query, $params, $field);

        $this->assertIsArray($rows);
    }

    /**
     * Проверка выборки массива записей, когда есть хоятбы одна а базе.
     *
     * @depends testCreateTableDB
     * @depends testInsert
     */
    public function testFetchArrayParams($db)
    {
        $query = 'SELECT * FROM `test_table` WHERE :id = :id ';
        $params = [':id' => 1];
        $field = '';

        $rows = $db->fetchArray($query, $params, $field);

        $this->assertIsArray($rows);
    }

    /**
     * Проверка выборки массива записей, когда есть хоятбы одна а базе.
     *
     * @depends testCreateTableDB
     * @depends testInsert
     */
    public function testFetchArrayField($db)
    {
        $query = 'SELECT * FROM `test_table`';
        $params = [];
        $field = 'id';

        $rows = $db->fetchArray($query, $params, $field);

        $this->assertIsArray($rows);
    }
}
