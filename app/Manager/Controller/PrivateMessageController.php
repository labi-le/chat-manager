<?php

declare(strict_types=1);


namespace Manager\Controller;


use Manager\Models\UserQuery;

class PrivateMessageController extends Controller
{
    public static function handler(array $data): void
    {
        parent::$db = new UserQuery($data['user_id']);
    }
}