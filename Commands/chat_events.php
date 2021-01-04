<?php

namespace labile\bot;

class Events extends Controller
{
    public static function chat_invite_user($id)
    {
        self::$vk->msg('invite user chat')->send();
    }

    public static function chat_invite_user_by_link($id)
    {
        print_r('invite user chat');
    }

    public static function chat_kick_user($id)
    {
        print_r('exit chat');
    }


    public static function chat_photo_update($id)
    {
        print_r('photo up chat');
    }

    public static function chat_photo_remove($id)
    {
        print_r('exit chat');
    }

    public static function chat_pin_message($id)
    {
        print_r('pin chat');
    }

    public static function chat_unpin_message($id)
    {
        print_r('nice cock');
    }

    public static function chat_screenshot($id)
    {
        self::$vk->msg('~негодяй|' . $id . '~ сделал скриншот')->send();
    }

}