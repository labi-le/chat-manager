<?php

namespace Manager\Models;

use DigitalStars\SimpleVK\LongPoll;
use DigitalStars\SimpleVK\SimpleVK;

/**
 * Личное дополнение к SimpleVK
 * Class SimpleVKExtend
 * @package Manager\Models
 */
class SimpleVKExtend
{
    /**
     * Массив с данными которые пришли от вк
     * есть также ванильный $this->data от SimpleVK
     * @var array
     */
    private static array $vars;

    /**
     * Парсинг всех данных которые пришли от вк в красивый вид
     * @param SimpleVK|LongPoll $vk
     * @return void ($this->vars)
     */
    public static function parse(SimpleVK|LongPoll $vk): void
    {
        $data = $vk->initVars($id, $user_id, $type, $message, $payload, $msg_id, $attachments);   // Парсинг полученных событий

        $chat_id = $id - 2e9;
        $chat_id = $chat_id > 0 ? (int)$chat_id : false;

        self::$vars['group_id'] = $data['group_id'];
        self::$vars['peer_id'] = $id ?? null;
        self::$vars['chat_id'] = $chat_id;
        self::$vars['user_id'] = $user_id ?? null;
        self::$vars['type'] = $type ?? null;
        self::$vars['text'] = $message ?? null;
        self::$vars['text_lower'] = mb_strtolower($message) ?? null;
        self::$vars['payload'] = $payload ?? false;
        self::$vars['action'] = $data['object']['action'] ?? false;
        self::$vars['message_id'] = $msg_id > 0 ? $msg_id : $data['object']['conversation_message_id'] ?? null;
        self::$vars['attachments'] = $attachments ?? null; //если вложений больше 4 то они не будут отображаться (баг вк), как костыль можно использовать getById
        self::$vars['fwd_messages'] = $data['object']['fwd_messages'] ?? [];
        self::$vars['reply_message'] = $data['object']['reply_message'] ?? [];

    }

    /**
     * Получить необходимые\все данные которые прислал вк
     * @param string|null $var
     * @return mixed
     */
    public static function getVars(string $var = null): mixed
    {
        $vars = self::$vars;
        return $vars[$var] ?? $vars;
    }
}
