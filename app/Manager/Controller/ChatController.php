<?php


namespace Manager\Controller;

use Manager\Models\ChatsQuery;

class ChatController extends Controller
{
    /**
     * Обработчик для бесед
     * Ну там подключение к базе и тд...
     */
    public static function handler(array $data)
    {
        parent::$db = new ChatsQuery($data['chat_id']);
        $data['action'] !== false ? ActionController::handler($data['action']) : null;
    }
}