<?php

namespace Manager\Controller;

final class TypeController extends Controller
{

    /**
     * Ивент: нажатие калбек кнопки
     * Event message_event
     * @param $data
     */
    public static function message_event(array $data): void
    {
        is_null($data['payload']) ?: CommandController::payloadHandler($data['payload']);
    }

    /**
     * Ивент: Новое сообщение
     * @param array $data
     */
    public static function message_new(array $data): void
    {
        if ($data['chat_id']) ChatController::handler($data);
        CommandController::commandHandler($data['text_lower']);
        if ($data['payload']) CommandController::payloadHandler($data['payload']);

    }

}