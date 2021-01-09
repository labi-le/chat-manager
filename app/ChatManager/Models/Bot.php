<?php

namespace ChatManager\Models;

use DigitalStars\SimpleVK\LongPoll as longpool;
use DigitalStars\SimpleVK\SimpleVK as callback;
use DigitalStars\SimpleVK\SimpleVkException;

//если нужен callback то просто переименуй
class Bot extends longpool
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

    public static function create($token, $version, $also_version = null): Bot
    {
        return new self($token, $version, $also_version);
    }

    /**
     * Управление многопоточностью
     * simplevk\longpool
     * @param bool $bool
     * @return Bot
     * @throws SimpleVkException
     */
    public function isMultiThread($bool = true): Bot
    {
        parent::isMultiThread($bool);
        return $this;

    }

    /**
     * Парсинг всех данных которые пришли от вк в красивый вид
     * @param void
     * @return void ($this->vars)
     */
    public function parse(): void
    {
        $this->initVars($id, $user_id, $type, $message, $payload, $msg_id, $attachments);   // Парсинг полученных событий

        $this->vars['peer_id'] = $id;
        $this->vars['user_id'] = $user_id;
        $this->vars['type'] = $type;
        $this->vars['text'] = $message;
        $this->vars['text_lower'] = mb_strtolower($message);
        $this->vars['payload'] = $payload;
        $this->vars['action'] = $this->data['object']['action'] ?? null;
        $this->vars['message_id'] = $msg_id > 0 ? $msg_id : $this->data['object']['conversation_message_id'] ?? null;
        $this->vars['attachments'] = $attachments; //если вложений больше 4 то они не будут отображаться (баг вк), как костыль можно использовать getById
        $this->vars['fwd_messages'] = $this->data['object']['fwd_messages'] ?? null;
        $this->vars['reply_message'] = $this->data['object']['reply_message'] ?? null;
    }

    /**
     * Получить необходимые\все данные которые прислал вк
     * @param string|null $var
     * @return mixed
     * @throws \Exception
     */
    public function getVars(string $var = null): mixed
    {
        if (isset($var)) return $this->vars[$var] ?? throw new \Exception('Попытка получить переменную которой впринципе нет'); else return $this->vars;
    }
}