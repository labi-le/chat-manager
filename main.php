<?php

namespace labile\bot;

use DigitalStars\SimpleVK\LongPoll as longpool;

require_once('./vendor/autoload.php');
require_once('./vendor/digitalstars/simplevk/autoload.php');

require_once('chat_events.php');
require_once('commands.php');

class ChatManager extends longpool
{
    use ChatEvents;
    use Commands;

    private $initVars;
    private $similar_percent = 75;

    public static function create($token, $version, $also_version = null)
    {
        return new self($token, $version, $also_version);
    }

    public function run()
    {
        $this->listen(function () {                       // Получение событий из LongPool
            $this->parse();
        });
    }

    /**
     * simplevk\longpool
     * @param bool $bool
     * @return $this|void
     * @throws \DigitalStars\SimpleVK\SimpleVkException
     */
    public function isMultiThread($bool = true)
    {
        parent::isMultiThread($bool);
        return $this;
    }

    private function handleType(&$type): void
    {
        if (method_exists($this, $type)) $this->$type($this->initVars);
    }

    /**
     * Event message_new
     */
    public function message_new($data): void
    {
        print_r($this->initVars);

        if (isset($data['action'])) $this->handleAction();

        $text_lower = $data['text_lower'];
        if (method_exists($this, $text_lower) && mb_strpos($text_lower, '_') === false) {
            $this->$text_lower();
        } else {
            $this->commandHandler();
        }
    }

    /**
     * Event message_new --> action
     */
    protected function handleAction(): void
    {
        $action = $this->initVars['action'];
        $type = $action['type'];

        ChatEvents::$type($action['member_id']);
    }

    protected function commandHandler()
    {
        if (method_exists($this, 'list')) {
            $list = $this->list();

            foreach ($list as $cmd) {
                if (!is_array($cmd['text'])) {
                    if ($this->formatText($cmd['text'])) {
                        $this->method_execute($cmd['method']);
                        break;
                    }
                } else {
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

    private function formatText(string $text)
    {
        if (mb_substr($text, 0, 1) == '|') {
            $pr = ($this->similar_percent != null) ? $this->similar_percent : 75;;
            return Utils::similarTo($text) > $pr / 100;
        }
        if (mb_substr($text, 0, 2) == "[|") {
            return Utils::startAs($text);
        }
        if (mb_substr($text, -2, 2) == "|]") {
            return Utils::endAs($text);
        }
        if (mb_substr($text, 0, 1) == "{" && mb_substr($text, -1, 1) == "}") {
            return Utils::contains($text);
        }

        return $text == $this->initVars['text_lower'];
    }

    /**
     * Выполнить метод\методы
     * @param $methods
     */
    private function method_execute($methods)
    {
        if (is_array($methods)) {
            foreach ($methods as $method) {
                $this->$method();
            }
        } else {
            $this->$methods();
        }
    }

    private function parse()
    {
        $this->initVars($id, $user_id, $type, $message, $payload, $msg_id, $attachments);   // Парсинг полученных событий
        $this->initVars['id'] = $id;
        $this->initVars['user_id'] = $user_id;
        $this->initVars['type'] = $type;
        $this->initVars['text'] = $message;
        $this->initVars['text_lower'] = mb_strtolower($message);
        $this->initVars['payload'] = $payload;
        $this->initVars['action'] = $this->data['object']['action'] ?? null;
        $this->initVars['msg_id'] = $msg_id;
        $this->initVars['attachments'] = $attachments;

        $this->handleType($type);
    }

    /**
     * Event message_event
     * @param $data
     */
    private function message_event($data): void
    {
    }
}

$vk = ChatManager::create('', 5.126)
    ->isMultiThread(true)  // многопоточить как сука???????? только линукс
    ->run();
