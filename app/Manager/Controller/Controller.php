<?php

namespace Manager\Controller;

use Manager\Commands\Commands;
use Manager\Models\Callback;
use Manager\Models\LongPoll;
use Manager\Models\QueryBuilder;

class Controller
{
    static Callback|LongPoll $vk;
    static QueryBuilder $db;

    /**
     * Вызов типа события и передача данных
     * @param array $data
     * @param Callback|LongPoll $bot
     * @return void
     */
    public static function handle(array $data, Callback|LongPoll $bot): void
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
     * @return void
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