<?php

namespace ChatManager\Controller;

use ChatManager\Commands\Events;

final class TypeController extends Controller
{

    /**
     * Ивент: нажатие калбек кнопки
     * Event message_event
     * @param $data
     */
    public static function message_event(array $data): void
    {
        //todo написать обработчик кнопок
        is_null($data['payload']) ?: CommandController::payloadHandler($data['payload']);
    }

    /**
     * Ивент: Новое сообщение
     * @param array $data
     */
    public static function message_new(array $data): void
    {
        $action = $data['action'];
        $member_id = $data['member_id'];

        if (isset($action)) self::handleAction($member_id);
        $text_lower = $data['text_lower'];

//        print_r($this->getVars()) . PHP_EOL;

        //если текст в сообщении == method name то он выполняет метод иначе ищет в массиве
        //чтоб не выполнял методы начинай название с черты _
        //(method_exists(Commands::class, $text_lower) && mb_strpos($text_lower, '_') === false) ? Commands::$text_lower() : CommandController::commandHandler($text_lower);

        CommandController::commandHandler($text_lower);

    }

}