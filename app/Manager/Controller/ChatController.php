<?php


namespace Manager\Controller;


use Manager\Models\ChatsQuery;
use Manager\Models\Connection;

class ChatController extends Controller
{
    public static function handler(array $data)
    {
        parent::$db = new ChatsQuery(Connection::make());
        parent::$db->id = $data['chat_id'];
        if ($data['action'] !== false) ActionController::handler($data['action']);
    }
}