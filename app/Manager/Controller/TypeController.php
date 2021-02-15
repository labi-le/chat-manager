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
        MessageController::parse($data);
    }

    /**
     * Ивент: Новое сообщение
     * @param array $data
     */
    public static function message_new(array $data): void
    {
        MessageController::parse($data);
    }

}