<?php


namespace Manager\Controller;

use Manager\Models\ChatsQuery;

class ChatController extends Controller
{
    public static function handler(array $data)
    {
        parent::$db = new ChatsQuery($data['chat_id']);
        if ($data['action'] !== false) ActionController::handler($data['action']);
    }
}