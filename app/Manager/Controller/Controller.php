<?php

namespace Manager\Controller;

use Manager\Commands\Commands;

class Controller
{
    static object $vk;

    /**
     * Вызов типа события и передача данных
     * @param array $data
     * @param object $bot
     * @return void
     */
    public static function handle(array $data, object $bot): void
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
                if (Commands::set(self::$vk)->$method() === false) break;
            }
        } else Commands::set(self::$vk)->$methods();
    }

}