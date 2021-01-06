<?php


namespace labile\bot;


class Controller
{
    static $vk;
    static $commandClass = 'Commands';
    static $eventClass = 'Events';

    /**
     * Вызов типа события и передача данных
     * @param string $type
     * @param array $vars
     * @param $vk
     * @return void
     */
    public static function handler(string $type, array $vars, $vk): void
    {
        self::$vk = $vk;
        if (method_exists($vk, $type)) $vk->$type($vars);

    }

    /**
     * обработка action (message\\action)
     * @param array $action
     * @return void
     */
    public static function handleAction(array $action): void
    {
        $type = $action['type'];
        $member_id = $action['member_id'];

        if (method_exists(self::$eventClass, $type)) self::$eventClass::$type($member_id);
    }


    /**
     * Выполнить метод\методы
     * @param array|string $methods
     * @return void
     */
    public static function method_execute(array|string $methods): void
    {
        if (is_array($methods)) {
            foreach ($methods as $method) self::$commandClass::$method();
        } else self::$commandClass::$methods();
    }

}