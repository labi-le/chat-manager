<?php

namespace labile\bot;

trait ChatEvents
{
    public function chat_invite_user()
    {
        print_r('invite user chat');
    }

    public function chat_invite_user_by_link()
    {
        print_r('invite user chat');
    }

    public function chat_kick_user($id)
    {
        print_r('exit chat');
    }


    public function chat_photo_update()
    {
        print_r('photo up chat');
    }

    public function chat_photo_remove()
    {
        print_r('exit chat');
    }

    public function chat_pin_message()
    {
        print_r('pin chat');
    }

    public function chat_unpin_message()
    {
        print_r('nice cock');
    }

}