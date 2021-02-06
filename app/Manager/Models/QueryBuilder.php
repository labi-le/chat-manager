<?php

namespace Manager\Models;

use PDO;

abstract class QueryBuilder implements IQuery
{
    public PDO $db;
    protected string $table;
    public int $id;

    /**
     * QueryBuilder constructor.
     * @param PDO $connection
     */
    public function __construct(PDO $connection)
    {
        $this->db = $connection;
    }

    /**
     * QueryBuilder set table
     * @param string $table
     * @return QueryBuilder
     */
    public function switchTable(string $table): QueryBuilder
    {
        $this->table = $table;
        return $this;
    }

    /**
     * Query
     * @param string $sql
     * @return array|bool
     */
    protected function query(string $sql): array|bool
    {
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get one|multiply|all record
     * Получить одну|множество|все записи
     * @param string|int|array|null $id
     * @return bool|array
     */
    private function get(string|int|array $id = null): bool|array
    {
        $sql = "SELECT * FROM $this->table";
        if (!is_null($id)) {
            if (is_array($id)) $id = implode(", ", $id);
            $sql .= " WHERE id IN ($id)";
        }
        $result = $this->query($sql);
        if ($result === []) return false; else return $result;
    }

    /**
     * Syntactic sugar for method get
     * Сахар для метода get
     * @param int $id
     * @return bool|array
     */
    public function getRecord(int $id): bool|array
    {
        return $this->get($id)[0] ?? false;
    }

    /**
     * Syntactic sugar for get
     * Сахар для метода get
     * @param array $ids
     * @return bool|array
     */
    public function getRecords(array $ids): bool|array
    {
        return $this->get($ids);
    }

    /**
     * Syntactic sugar for method get
     * Сахар для метода get
     * @return bool|array
     */
    public function getAllRecords(): bool|array
    {
        return $this->get();
    }

    /**
     * Create record in table
     * Создать запись в таблице
     * ['id' => 1, 'name' => 'John', etc...]
     * @param array $params
     * @return bool
     */
    public function createRecord(array $params): bool
    {
        $keys = implode("`, `", array_keys($params));
        $values = implode("','", $params);
        $sql = "INSERT INTO " . $this->table . "(`" . $keys . "`) VALUES ('" . $values . "')";
        return (bool)$this->query($sql);
    }

    /**
     * Delete record
     * Удалить запись
     * @param int|array $id
     * @return bool
     */
    public function deleteRecord(int|array $id): bool
    {
        $sql = "DELETE FROM $this->table WHERE id IN(";
        if (is_array($id)) $sql .= implode(", ", $id) . ')';
        else $sql .= $id . ')';

        return (bool)$this->query($sql);
    }

    /**
     * Show interval of records
     * Показать интервал записей
     * @param int $start
     * @param int $end
     * @param string $column
     * @return array|bool
     */
    public function getIntervalRecords(int $start, int $end, string $column = 'id'): array|bool
    {
        return $this->query("SELECT * FROM $this->table WHERE $column >= $start AND $column <= $end");
    }

    /**
     * Delete table
     * Удалить таблицу
     * @return bool
     */
    public function deleteTable(): bool
    {
        return (bool)$this->db->query("DROP TABLE $this->table");
    }
}
