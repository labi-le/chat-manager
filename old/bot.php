<?php

namespace labile\bot;

use DigitalStars\SimpleVK\LongPoll as longpool;
use DigitalStars\SimpleVK\SimpleVK as callback;

//если нужен callback то просто переименуй
class ChatManager extends longpool
{
    use Controller;
    use Events;
    use Commands;

    protected array $vars;

    private int $similar_percent = 75;

    public static function create($token, $version, $also_version = null): ChatManager
    {
        return new self($token, $version, $also_version);
    }

    /**
     * simplevk\longpool
     * @param bool $bool
     * @return ChatManager
     * @throws \DigitalStars\SimpleVK\SimpleVkException
     */
    public function isMultiThread($bool = true): ChatManager
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

        $this->handler($type, $this->vars);
    }

    /**
     * @param string|null $var
     * @return mixed
     * @throws \Exception
     */
    public function getVars(string $var = null): mixed
    {
        if (isset($var)) return $this->vars[$var] ?? throw new \Exception('Попытка получить переменную которой впринципе нет'); else return $this->vars;
    }

    /**
     * Event message_event
     * @param $data
     */
    private function message_event($data): void
    {
        //todo написать обработчик кнопок
        is_null($data['payload']) ?: $this->payloadHandler();
    }

    /**
     * Ивент Новое сообщение
     * @param array $data
     */
    private function message_new(array $data): void
    {
        if (isset($data['action'])) $this->handleAction($data['action'], $this);
        $text_lower = $data['text_lower'];

        //если текст в сообщении == method name то он выполняет метод иначе ищет в массиве
        //чтоб не выполнял методы начинай название с черты _
        (method_exists($this, $text_lower) && mb_strpos($text_lower, '_') === false) ? $this->$text_lower() : $this->commandHandler();

    }
}