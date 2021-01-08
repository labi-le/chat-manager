<?php

namespace labile\bot;

class Events extends Controller
{
    /**
     * Пользователь присоединился к беседе
     * @param $id
     * @return void
     */
    public static function chat_invite_user($id): void
    {
        self::$vk->msg('invite user chat')->send();
    }

    /**
     * Пользователь присоединился к беседе по инвайт-ссылке
     * @param $id
     * @return void
     */
    public static function chat_invite_user_by_link($id)
    {
        self::$vk->msg('invite user chat from link')->send();
    }

    /**
     * Пользователь покинул беседу, либо был исключён кикнули
     * @param $id
     * @return void
     */
    public static function chat_kick_user($id)
    {
        print_r('exit chat');
    }

    /**
     * Обновлена аватарка
     * @param $id
     */
    public static function chat_photo_update($id)
    {
        print_r('photo up chat');
    }

    /**
     * Удалена аватарка
     * @param $id
     */
    public static function chat_photo_remove($id)
    {
        print_r('exit chat');
    }

    /**
     * Закреплено сообщение
     * @param $id
     */
    public static function chat_pin_message($id)
    {
        print_r('pin chat');
    }

    /**
     * Откреплено сообщение
     * @param $id
     */
    public static function chat_unpin_message($id)
    {
        print_r('nice cock');
    }

    /**
     * Сделан скриншот
     * @param $id
     */
    public static function chat_screenshot($id)
    {
        self::$vk->msg('~негодяй|' . $id . '~ сделал скриншот')->send();
    }

}