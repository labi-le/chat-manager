<?php

namespace Manager\Models;

use PDO;

class QueryBuilder
{
    use ChatsQuery;

    public PDO $db;

    public function __construct(PDO $connection)
    {
        $this->db = $connection;
    }

    public function get(string $table, string|int|array $id = null)
    {
        $sql = "SELECT * FROM $table";
        if (!is_null($id)) {
            if (is_array($id)) $id = implode(", ", $id);
            $sql .= " WHERE id IN ($id)";
        }
        $result = $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);

        if ($result === []) return false; else return $result;
    }


    public function getTable($table)
    {
        return $this->get($table);
    }

    public function getRecord($table, $id)
    {
        return $this->get($table, $id);
    }

    /**
     * Создать запись в таблице
     * @param string $table
     * @param array $params
     * @return bool
     */
    public function createRecord(string $table, array $params): bool
    {
        $keys = implode("`, `", array_keys($params));
        $values = implode("','", $params);
        $sql = "INSERT INTO " . $table . "(`" . $keys . "`) VALUES ('" . $values . "')";
        return (bool)$this->db->query($sql);
    }

    public function deleteRecord(string $table, int|array $id): bool
    {
        $sql = "DELETE FROM $table WHERE id IN(";
        if (is_array($id)) $sql .= implode(", ", $id) . ')';
        else $sql .= $id . ')';

        return (bool)$this->db->query($sql);
    }
}
