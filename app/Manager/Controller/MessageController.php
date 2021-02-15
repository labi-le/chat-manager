<?php

namespace Manager\Controller;

use Manager\Commands\CommandList;
use Manager\Models\Utils;

final class MessageController extends Controller
{
    public static function parse(array $data)
    {
        /**
         * Если это беседа
         */
        if ($data['chat_id']) ChatController::handler($data);

        /**
         * Поиск и выполнение команд по тексту сообщения
         */
        self::commandHandler($data['text_lower']);

        /**
         * Поиск и выполнение команд по кнопке
         */
        if ($data['payload'] !== false) {
            /**
             * На десктопной версии контакта не работают калбек кнопки, делаем заглушку...
             */
            $data['payload']['command'] == 'not_supported_button'
                ? self::payloadHandler(['command' => 'not_supported_button'], 'callback')
                : self::payloadHandler($data['payload']);
        }
    }

    /**
     * Поиск и выполнение команд (если нашел)
     * @param string $originalText
     * @return void
     */
    private static function commandHandler(string $originalText): void
    {
        $list = CommandList::text();
        if (is_array($list)) {

            foreach ($list as $cmd) {
                if (!is_array($cmd['text'])) {
                    if (Utils::formatText(( string)$cmd['text'], $originalText)) {
                        self::method_execute($cmd['method']);
                        break;
                    }
                }

                if (is_array($cmd['text'])) {
                    foreach ($cmd['text'] as $textFromArray) {
                        if (Utils::formatText($textFromArray, $originalText)) {
                            self::method_execute($cmd['method']);
                            break;
                        }

                    }
                }
            }
        }
    }

    /**
     * Обработчик нажатий по клавиатуре
     * type == 'default' - обычные кнопки
     * type == 'callback' - калбек кнопки
     * @param array $payload
     * @param string $type
     * @return void
     */
    private static function payloadHandler(array $payload, string $type = 'default'): void
    {
        $payloads = CommandList::payload();
        $key = key($payload);
        $value = current($payload);

        foreach ($payloads[$key] as $array) {
            if ($value === $array['payload'] and $array['type'] === $type) self::method_execute($array['method']);
        }
    }

}