<?php

namespace Manager\Models;

interface IQuery
{
    public function deleteTable(): bool;
    public function createRecord(array|int $params): bool;
    public function getRecord(int $id): array|null;
    public function deleteRecord(int $id): bool;
}