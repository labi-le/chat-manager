<?php

namespace Manager\Controller;

use Manager\Commands\Commands;
use Manager\Models\ChatsQuery;
use Manager\Models\ExtendSimpleVKCallback;
use Manager\Models\ExtendSimpleVKLongPoll;
use Manager\Models\QueryBuilder;

class Controller
{
    static ExtendSimpleVKCallback|ExtendSimpleVKLongPoll $vk;
    static QueryBuilder|ChatsQuery $db;

    /**
     * Вызов типа события и передача данных
     */
    public static function handle(array $data, ExtendSimpleVKCallback|ExtendSimpleVKLongPoll $bot): void
    {
        $type = $data['type'];
        if (method_exists(TypeController::class, $type)) {
            self::$vk = $bot;
            TypeController::$type($data);
        }

    }

    /**
     * Выполнить метод\методы
     */
    public static function method_execute(array|string $methods): void
    {
        if (is_array($methods)) {
            foreach ($methods as $method) {
                if (Commands::set(self::$vk, self::$db)->$method() === false) break;
            }
        } else Commands::set(self::$vk, self::$db)->$methods();
    }

}