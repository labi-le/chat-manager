<?php

declare(strict_types=1);


namespace Manager\Models;


class UserQuery extends QueryBuilder
{
    protected string $store_name = 'users';

    protected function __generateTable(int $id): array
    {
        return
            [
                self::ID => $id,
                'actions' => []
            ];
    }
}