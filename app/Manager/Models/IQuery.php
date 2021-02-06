<?php

namespace Manager\Models;

interface IQuery
{
    public function deleteTable(): bool;
    public function createRecord(array $params): bool;
    public function getRecord(int $id): array|bool;
    public function getAllRecords(): array|bool;
    public function getRecords(array $ids): array|bool;
    public function getIntervalRecords(int $start, int $end, string $column): array|bool;
    public function deleteRecord(int $id): bool;
}