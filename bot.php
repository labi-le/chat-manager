<?php

namespace labile\bot;

use DigitalStars\SimpleVK\LongPoll as longpool;
use DigitalStars\SimpleVK\SimpleVkException;

class ChatManager extends longpool
{
    use Events;
    use Commands;

    protected array $vars;
    private int $similar_percent = 75;

    public static function create($token, $version, $also_version = null): ChatManager
    {
        return new self($token, $version, $also_version);
    }

    public function run()
    {
        $this->listen(function () {                       // Получение событий из LongPool
            $this->parse();
        });
    }

    private function parse(): void
    {
        $this->initVars($id, $user_id, $type, $message, $payload, $msg_id, $attachments);   // Парсинг полученных событий

        $this->vars['id'] = $id;
        $this->vars['user_id'] = $user_id;
        $this->vars['type'] = $type;
        $this->vars['text'] = $message;
        $this->vars['text_lower'] = mb_strtolower($message);
        $this->vars['payload'] = $payload;
        $this->vars['action'] = $this->data['object']['action'] ?? null;
        $this->vars['message_id'] = $msg_id > 0 ? $msg_id : $this->data['object']['conversation_message_id'];
        $this->vars['attachments'] = $attachments;
        $this->vars['fwd_messages'] = $this->data['object']['fwd_messages'] ?? null;
        $this->vars['reply_message'] = $this->data['object']['reply_message'] ?? null;

        $this->handleType($type);
    }

    /**
     * Обработка Ивента
     * @param $type
     */
    private function handleType($type)
    {
        if (method_exists($this, $type)) $this->$type($this->getVars());
    }

    /**
     * simplevk\longpool
     * @param bool $bool
     * @return $this|void
     * @throws SimpleVkException
     */
    public function isMultiThread($bool = true): ChatManager
    {
        parent::isMultiThread($bool);
        return $this;
    }

    /**
     * Ивент Новое сообщение
     * @param mixed $data
     */
    private function message_new($data): void
    {
        print_r($this->vars);

        if (isset($data['action'])) $this->handleAction();

        $text_lower = $data['text_lower'];

        //если текст в сообщении == method name то он выполняет метод иначе ищет в массиве
        //чтоб не выполнял методы начинай название с черты _ чёрт
        (method_exists($this, $text_lower) && mb_strpos($text_lower, '_') === false) ? $this->$text_lower() : $this->commandHandler();

    }

    /**
     * обработка action
     * @param void
     */
    protected function handleAction(): void
    {
        $action = $this->getVars()['action'];
        $type = $action['type'];

        if (method_exists($this, $type)) $this->$type($action['member_id']);
    }

    protected function commandHandler()
    {
        if (method_exists($this, 'list')) {
            $list = $this->list();

            Utils::setText($this->getVars()['text_lower']);

            foreach ($list as $cmd) {
                if (!is_array($cmd['text'])) {
                    if ($this->formatText((string)$cmd['text'])) {
                        $this->method_execute($cmd['method']);
                        break;
                    }
                } elseif (is_array($cmd['text'])) {
                    foreach ($cmd['text'] as $text) {
                        if ($this->formatText($text)) {
                            $this->method_execute($cmd['method']);
                            break;
                        }
                    }
                }
            }
        }
    }

    /**
     * проверка
     * @param string $text
     * @return bool
     */
    protected function formatText(string $text): bool
    {
        Utils::setText($this->getVars()['text_lower']);

        if (mb_substr($text, 0, 1) == '|') {
            $pr = ($this->similar_percent != null) ? $this->similar_percent : 75;
            return Utils::similarTo($text) > $pr;
        } elseif (mb_substr($text, 0, 2) == "[|") {
            return Utils::startAs($text);
        } elseif (mb_substr($text, -2, 2) == "|]") {
            return (Utils::endAs($text));
        } elseif (mb_substr($text, 0, 1) == "{" && mb_substr($text, -1, 1) == "}") {
            return (Utils::contains($text));
        } else return $text == Utils::getText();
    }

    /**
     * @param $var
     */
    public function getVars(string $var = null): array|string
    {
        if (isset($var)) return $this->vars[$var]; else return $this->vars;
    }

    /**
     * Выполнить метод\методы
     * @param array|string $methods
     */
    private function method_execute($methods)
    {
        if (is_array($methods)) {
            foreach ($methods as $method) $this->$method();
        } else $this->$methods();
    }

    /**
     * Event message_event
     * @param $data
     */
    private function message_event($data): void
    {
        //todo написать обработчик кнопок
    }
}