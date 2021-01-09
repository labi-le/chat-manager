<?php
namespace ChatManager\Controller;

use ChatManager\Commands\Commands;
use ChatManager\Commands\Events;
use ChatManager\Models\Bot;

class Controller
{
    static $vk;

    /**
     * Вызов типа события и передача данных
     * @param string $type
     * @param array $data
     * @return bool
     */
    public static function handle(string $type, array $data): bool
    {
        if (method_exists(self::class, $type)) TypeController::$data($data);
    }

    /**
     * обработка action (message\\action)
     * @param array $action
     * @return void
     */
    protected static function handleAction(array $action): void
    {
        $type = $action['type'];
        $member_id = $action['member_id'];

        if (method_exists(Events::class, $type)) Events::$type($member_id);
    }


    /**
     * Выполнить метод\методы
     * @param array|string $methods
     * @return void
     */
    public static function method_execute(array|string $methods): void
    {
        if (is_array($methods)) {
            foreach ($methods as $method) Commands::set(self::$vk)->$method();
        } else Commands::set(self::$vk)->$methods();
    }

}