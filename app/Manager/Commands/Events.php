<?php

namespace Manager\Commands;

final class Events
{
    /**
     * Пользователь присоединился к беседе
     * @param $id
     */
    public static function chat_invite_user($id)
    {
    }

    /**
     * Пользователь присоединился к беседе по инвайт-ссылке
     * @param $id
     * @return void
     */
    public static function chat_invite_user_by_link($id)
    {
    }

    /**
     * Пользователь покинул беседу, либо был исключён кикнули
     * @param $id
     * @return void
     */
    public static function chat_kick_user($id)
    {
    }

    /**
     * Обновлена аватарка
     * @param $id
     */
    public static function chat_photo_update($id)
    {
    }

    /**
     * Удалена аватарка
     * @param $id
     */
    public static function chat_photo_remove($id)
    {
    }

    /**
     * Закреплено сообщение
     * @param $id
     */
    public static function chat_pin_message($id)
    {
    }

    /**
     * Откреплено сообщение
     * @param $id
     */
    public static function chat_unpin_message($id)
    {
    }

    /**
     * Сделан скриншот
     * @param $id
     */
    public static function chat_screenshot($id)
    {
    }

}