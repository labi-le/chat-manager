<?php

declare(strict_types=1);


namespace Manager\Controller;

use Manager\Models\ChatsQuery;

class ChatController extends Controller
{
    /**
     * Обработчик для бесед
     * Ну там подключение к базе и тд...
     * @param array $data
     */
    public static function handler(array $data)
    {
        parent::$db = new ChatsQuery($data['chat_id']);
        if (isset($data['action'])) ActionController::handler($data['action']);
    }
}