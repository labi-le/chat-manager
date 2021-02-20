<?php

namespace Manager\Controller;

use DigitalStars\SimpleVK\SimpleVK;
use Manager\Commands\Commands;
use Manager\Models\ChatsQuery;
use Manager\Models\QueryBuilder;

class Controller
{
    static SimpleVK $vk;
    static QueryBuilder|ChatsQuery $db;

    /**
     * Вызов типа события и передача данных
     * @param array $data
     * @param SimpleVK $bot
     */
    public static function handle(array $data, SimpleVK $bot): void
    {
        $type = $data['type'];
        if (method_exists(TypeController::class, $type)) {
            self::$vk = $bot;
            TypeController::$type($data);
        }

    }

    /**
     * Выполнить метод\методы
     * @param array|string $methods
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