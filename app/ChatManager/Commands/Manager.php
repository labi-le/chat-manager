<?php

namespace ChatManager\Commands;

use ChatManager\Models\Utils;
use Exception;

/**
 * Трейт для команд подходящих под категорию чат пидорства и блядства
 * @package labile\bot
 */
trait Manager
{
    //todo написать команды для чм

    /**
     * Кикнуть пользователя
     */
    public function kick()
    {
        $reply = $this->vk->getVars('reply_message');
        $fwd = $this->vk->getVars('fwd_messages');
        $chat_id = $this->vk->getVars('chat_id');
        $text = Utils::removeFirstWord($this->vk->getVars('text'));

        $result = $this->kickFromReplyMsg($chat_id, $reply) ?? $result = $this->kickFromForwardMsg($chat_id, $fwd) ?? $this->kickFromText($chat_id, $text) ?? false;

        if (!$result) {
            $this->vk->msg('Кого?')->send();
        } else {
            $status = $this->kickStatus($result);

            $string = null;
            if (!empty(current($status))) {
                $string .= 'Kicked: ' . $status[1] . PHP_EOL;
            }
            if (!empty(next($status))) {
                $string .= 'Не удалось кикнуть: ' . $status[15] . PHP_EOL;
            }
            if (!empty(next($status))) {
                $string .= 'Их вообще нет в конфе: ' . $status[935] . PHP_EOL;
            }

            $this->vk->msg($string)->send();
        }
    }

    /**
     * Получить статус кика в симпатичном виде
     * @param $data
     * @return array
     */
    private function kickStatus(array $data): array
    {
        $text[1] = null;
        $text[15] = null;
        $text[935] = null;

        foreach ($data as $member_id => $status) {
            match ($status) {
                1 => $text[1] .= $member_id > 0 ? "@id$member_id " : -$member_id . ' ',
                15 => $text[15] .= $member_id > 0 ? "@id$member_id " : -$member_id . ' ',
                935 => $text[935] .= $member_id > 0 ? "@id$member_id " : -$member_id . ' ',
            };
        }
        return $text;

    }

    private function kickFromText(int $chat_id, string $string): array|null
    {
        $member_ids = Utils::regexId($string);

        $results = null;
        foreach ($member_ids as $id) {
            $results[$id] = $this->removeChatUser($chat_id, $id);
        }
        return $results;
    }

    /**
     * Кикнуть пользователя сообщение которого переслали
     * @param int $chat_id
     * @param array $array
     * @return array|null
     */
    private function kickFromForwardMsg(int $chat_id, array $array): array|null
    {
        if (empty($array)) return null;

        $results = null;
        foreach ($array as $message) {
            $results[$message['from_id']] = $this->removeChatUser($chat_id, $message['from_id']);
        }
        return $results;
    }

    /**
     * Кикнуть пользователя сообщение на которое ответили
     * @param int $chat_id
     * @param array|null $array $array
     * @return array|null
     */
    private function kickFromReplyMsg(int $chat_id, array|null $array): array|null
    {
        if (empty($array)) return null;

        $results = null;
        $results[$array['from_id']] = $this->removeChatUser($chat_id, $array['from_id']);

        return $results;
    }

    /**
     * Метод vkapi для исключения пользователя|бота или самого себя (при указании своего айди)
     * @param int $chat_id
     * @param int $member_id
     * @param int|null $user_id
     * @return bool|int
     */
    private function removeChatUser(int $chat_id, int $member_id, int $user_id = null): bool|int
    {
        try {
            $this->vk->request('messages.removeChatUser',
                ['chat_id' => $chat_id, 'member_id' => $member_id, 'user_id' => $user_id]);
        } catch (Exception $e) {
            return $e->getCode();
        }

        return 1;
    }
}