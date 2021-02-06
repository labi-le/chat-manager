<?php

namespace Manager\Models;

use Exception;

trait Bot
{

    /**
     * Массив с данными которые пришли от вк
     * есть также ванильный $this->data от SimpleVK
     * @var array
     */
    protected array $vars;

    /**
     * Процент срабатывания в методе formatText классе Utils
     * Utils::formatText(string $textFromArray, string $original, $similarPercent = 75)
     * @var int
     */
    private int $similar_percent = 75;

    public static function create($token, $version, $also_version = null)
    {
        return new self($token, $version, $also_version);
    }

    /**
     * Парсинг всех данных которые пришли от вк в красивый вид
     * @param void
     * @return void ($this->vars)
     */
    public function parse(): void
    {
        $this->initVars($id, $user_id, $type, $message, $payload, $msg_id, $attachments);   // Парсинг полученных событий

        $chat_id = $id - 2e9;
        $chat_id = $chat_id > 0 ? (int)$chat_id : false;

        $this->vars['group_id'] = $this->data['group_id'];
        $this->vars['peer_id'] = $id ?? null;
        $this->vars['chat_id'] = $chat_id;
        $this->vars['user_id'] = $user_id ?? null;
        $this->vars['type'] = $type ?? null;
        $this->vars['text'] = $message ?? null;
        $this->vars['text_lower'] = mb_strtolower($message) ?? null;
        $this->vars['payload'] = $payload ?? false;
        $this->vars['action'] = $this->data['object']['action'] ?? false;
        $this->vars['message_id'] = $msg_id > 0 ? $msg_id : $this->data['object']['conversation_message_id'] ?? null;
        $this->vars['attachments'] = $attachments ?? null; //если вложений больше 4 то они не будут отображаться (баг вк), как костыль можно использовать getById
        $this->vars['fwd_messages'] = $this->data['object']['fwd_messages'] ?? [];
        $this->vars['reply_message'] = $this->data['object']['reply_message'] ?? [];

    }

    /**
     * Получить необходимые\все данные которые прислал вк
     * @param string|null $var
     * @return mixed
     * @throws Exception
     */
    public function getVars(string $var = null): mixed
    {
        if (isset($var, $this->vars[$var])) return $this->vars[$var]; else return $this->vars;
    }

    public function reply(string $text): void
    {
        $this->msg($text)->send();
    }
}