<?php

namespace ChatManager\Commands;

/**
 * Трейт для команд подходящих под категорию чат пидорства и блядства
 * @package labile\bot
 */

trait Manager
{
    /**
     * Кикнуть пользователя
     */
    public function kick()
    {
        $fwd = $this->vk->getVars('fwd_messages');
        $reply = $this->vk->getVars('reply_message');
        $chat_id = $this->vk->getVars('chat_id');

        /**
         * Метод vkapi для исключения пользователя|бота
         * @param int $chat_id
         * @param int $member_id
         * @param int|null $user_id
         */
        $kick = function (int $chat_id, int $member_id, int $user_id = null)
        {
            $this->vk->request('messages.removeChatUser',
                ['chat_id' => $chat_id, 'member_id' => $member_id, 'user_id' => $user_id]);
        };

        if (!empty($reply)) {
            $kick($chat_id, $reply['from_id']);
        } elseif (!empty($fwd)) {
            foreach ($fwd as $message){
                $kick($chat_id, $message['from_id']);
            }

        } else $this->vk->msg('но репли но фвд')->send();


//        $this->vk->msg(print_r($this->vk->getVars(), true))->send();
    }

    //todo написать команды для чм
}