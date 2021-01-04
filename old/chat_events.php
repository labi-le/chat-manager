<?php

namespace labile\bot;

trait Events
{
    protected function chat_invite_user($id)
    {
        print_r('invite user chat');
    }

    protected function chat_invite_user_by_link($id)
    {
        print_r('invite user chat');
    }

    protected function chat_kick_user($id)
    {
        print_r('exit chat');
    }


    protected function chat_photo_update($id)
    {
        print_r('photo up chat');
    }

    protected function chat_photo_remove($id)
    {
        print_r('exit chat');
    }

    protected function chat_pin_message($id)
    {
        print_r('pin chat');
    }

    protected function chat_unpin_message($id)
    {
        print_r('nice cock');
    }

    protected function chat_screenshot($id)
    {
        $this->msg('~негодяй|' . $id . '~ сделал скриншот')->send();
    }

}