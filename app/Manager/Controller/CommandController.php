<?php

namespace Manager\Controller;

use Manager\Commands\CommandList;
use Manager\Models\Utils;

final class CommandController extends Controller
{
    /**
     * Поиск и выполнение команд (если нашел)
     * @param string $originalText
     * @return void
     */
    public static function commandHandler(string $originalText): void
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
     * @param array $payload
     * @return void
     */
    public static function payloadHandler(array $payload): void
    {
        $payloads = CommandList::payload();
        if (is_array($payloads)) {
            $key = key($payload);
            $value = current($payload);

            $method = $payloads[$key][$value]['method'] ?? null;

            if (isset($method)) self::method_execute($method);
        }
    }
}