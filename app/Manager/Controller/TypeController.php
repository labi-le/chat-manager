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
        $action = $data['action'];

        if (isset($action)) ActionController::handleAction($action);
        $text_lower = $data['text_lower'];
        CommandController::commandHandler($text_lower);

    }

}