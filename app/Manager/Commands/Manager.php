<?php

namespace Manager\Commands;

use Exception;
use Manager\Models\Utils;

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
        $vars = $this->vk->getVars();
        $chat_id = $vars['chat_id'];
        $text = Utils::removeFirstWord($this->vk->getVars('text'));

        $result = $this->kickFromReplyMsg($chat_id, $vars['reply_message']) ?? $result = $this->kickFromForwardMsg($chat_id, $vars['fwd_messages']) ?? $this->kickFromText($chat_id, $text) ?? false;

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
        $text[1] = null; //успешно кикнут
        $text[15] = null; //не получилось
        $text[935] = null; //нет в беседе

        foreach ($data as $member_id => $status) {
            match ($status) {
                1 => $text[1] .= $member_id > 0 ? "~!fn|$member_id~ " : -$member_id . ' ',
                15 => $text[15] .= $member_id > 0 ? "~!fn|$member_id~ " : -$member_id . ' ',
                935 => $text[935] .= $member_id > 0 ? "~!fn|$member_id~ " : -$member_id . ' ',
            };
        }
        return $text;

    }

    /**
     * Кик по упоминанию
     * @param int $chat_id
     * @param string $string
     * @return array|null
     */
    private function kickFromText(int $chat_id, string $string): array|null
    {
        $array = Utils::regexId($string);
        return $this->smartKick($chat_id, $array);
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
        return $this->smartKick($chat_id, $array);
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
        return $this->smartKick($chat_id, $array);
    }

    /**
     * Метод vkapi для исключения пользователя|бота или самого себя (при указании своего айди)
     * @param int $chat_id
     * @param int $member_id
     * @param int|null $user_id
     * @return int
     */
    private function removeChatUser(int $chat_id, int $member_id, int $user_id = null): int
    {
        try {
            $this->vk->request('messages.removeChatUser',
                ['chat_id' => $chat_id, 'member_id' => $member_id, 'user_id' => $user_id]);
        } catch (Exception $e) {
            return $e->getCode();
        }
        return 1;
    }

    /**
     * Простой и сексуальный кикер
     * @param int $chat_id
     * @param mixed $member_ids
     * @param mixed $user_ids
     * @return array|null
     */
    private function smartKick(int $chat_id, mixed $member_ids, mixed $user_ids = null): array|null
    {
        $array = $user_ids ?? $member_ids;
        $results = null;
        if (Utils::isMulti($array)) {
            if (!isset($array[0])) $array = [0 => $array]; //Если сообщение является ответом
            foreach ($array as $arr) {
                $results[$arr['from_id']] = $this->removeChatUser($chat_id, $arr['from_id']);
            }
        } elseif (Utils::isSeq($array)) {
            foreach ($array as $arr) {
                $results[$arr] = $this->removeChatUser($chat_id, (int)$arr);
            }
        }
        return $results;
    }
}