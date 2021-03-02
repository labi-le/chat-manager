<?php

declare(strict_types=1);

namespace Manager\Models;

use Adbar\Dot;
use DigitalStars\SimpleVK\LongPoll;
use DigitalStars\SimpleVK\SimpleVK;
use Exception;
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
        $SimpleVKData = $vk->initVars($id, $user_id, $type, $message, $payload, $msg_id, $attachments);   // Парсинг полученных событий

        $data = static function (string $get) use ($SimpleVKData) {
            return dot($SimpleVKData)->get($get);
        };

        $var = new Dot();

        $chat_id = $id - 2e9;
        $chat_id = $chat_id > 0 ? (int)$chat_id : null;

        $data('group_id') === null ?: $var->add('group_id', $data('group_id'));
        $id === null ?: $var->add('peer_id', $id);
        $chat_id === null ?: $var->add('chat_id', $chat_id);
        $user_id === null ?: $var->add('user_id', $user_id);
        $type === null ?: $var->add('type', $type);
        $message === null ?: $var->add('text', $message);
        $message === null ?: $var->add('text_lower', mb_strtolower($message));
        $payload === null ?: $var->add('payload', $payload);
        $data('object.message.action') === null ?: $var->add('action', $data('object.message.action'));
        $msg_id === null ?: $var->add('message_id', $msg_id);

        $data('object.message.conversation_message_id') === null ?: $var->add('conversation_message_id', $data('object.message.conversation_message_id'));
        $data('object.conversation_message_id') === null ?: $var->add('conversation_message_id', $data('object.conversation_message_id'));

        $attachments === null ?: $var->add('attachments', $attachments); //если вложений больше 4 то они не будут отображаться (баг вк), как костыль можно использовать getById
        $data('object.message.fwd_messages') === null ?: $var->add('fwd_messages', $data('object.message.fwd_messages'));
        $data('object.message.reply_message') === null ?: $var->add('reply_message', $data('object.message.reply_message'));

        self::$vars = $var->all();
    }

    /**
     * Получить необходимые\все данные которые прислал вк
     * @param string|null $var
     * @return mixed
     */
    public static function getVars(string $var = null): mixed
    {
        if ($var === null) {
            return self::$vars;
        }

        if (is_string($var) and isset(self::$vars[$var])) {
            return self::$vars[$var];
        }

        return null;
    }
    
    /**
     * Получить всех менеджеров в группе
     * @param SimpleVK $vk
     * @param int $group_id
     * @return array|false
     * @throws Exception
     */
    public static function isManagerGroup(SimpleVK $vk, int $group_id): array|false
    {
        try {
            $response = $vk->request('groups.getMembers',
                [
                    'group_id' => $group_id,
                    'filter' => 'managers'
                ]
            );
        } catch (Exception) {
            throw new \RuntimeException('Токен не имеет доступа к менеджерам группы');
        }

        if (isset($response['count']) && $response['count'] > 0) {
            $ids = null;
            foreach ($response['items'] as $item) {
                $ids[] = $item['id'];
            }
            return $ids;
        }

        return false;
    }

}
