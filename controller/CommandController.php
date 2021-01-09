<?php

namespace labile\bot;

class CommandController extends Controller
{
    /**
     * Поиск и выполнение команд (если нашел)
     * @param string $originalText
     * @return void
     */
    public static function commandHandler(string $originalText): void
    {
        if (CommandList::text()) {
            $list = CommandList::text();

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
     * @param array $payload
     * @return void
     */
    public static function payloadHandler(array $payload): void
    {
        //todo написать обработчик кнопок типа payload и калбек кнопок
    }
}