<?php


namespace Manager\Models;


use SleekDB\Store;

abstract class QueryBuilder implements IQuery
{

    /**
     * Корневая директория в которой будут лежать данные
     * app, vendor, etc...
     */
    const DATA_DIR = __DIR__ . '/../../../database/';

    /**
     * Название идентификатора
     */
    const ID = 'id';


    /**
     * Стандартные настройки для базы данных
     * https://sleekdb.github.io/#/configurations
     */
    const CONFIGURATION_DB =
        [
            "auto_cache" => false,
            "cache_lifetime" => null,
            "timeout" => 120,
//            "primary_key" => self::ID
        ];

    protected Store $db;
    protected string $store_name;

    public function __construct()
    {
        $this->db = new Store($this->store_name, self::DATA_DIR, self::CONFIGURATION_DB);
    }

    public function deleteTable(): bool
    {
        return $this->db->deleteStore();
    }

    public function createRecord(array|int $params): bool
    {
        return (bool)$this->db->insert($params);
    }

    public function getRecord(int $id): array|null
    {
        return $this->db->findOneBy([self::ID, '=', $id]);
    }

    public function getAllRecords(): array|null
    {
        return $this->db->findAll();
    }

    public function deleteRecord(int $id): bool
    {
        return $this->db->deleteById($id);
    }

    public function getDBName(): string
    {
        return $this->store_name;
    }

}