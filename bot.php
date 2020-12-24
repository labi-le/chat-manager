<?php

// 

use DigitalStars\SimpleVK\LongPoll as longpool;

require_once('./vendor/digitalstars/simplevk/autoload.php');

class ChatManager extends longpool
{
    public $initVars;

    private function say($text)
    {
        $this->msg($text)->send();
    }

    public static function create($token, $version, $also_version = null)
    {
        return new self($token, $version, $also_version);
    }

    public function run()
    {
        $this->isMultiThread(true);  // ну потоки же круто почему нет как бы

        $lexicalThis = $this; // this is lexical зато можно rename
        $this->listen(function () use ($lexicalThis) {                       // Получение событий из LongPool
            // $data = $this->initVars($id, $user_id, $type, $message, $payload, $msg_id, $attachments);   // Парсинг полученных событий

            $data = $this->data;
            $this->initVars = call_user_func(function () use ($data) {
                $new_data = null;
                
                isset($_msg) ? $new_data = $data['object']['message'] : $new_data = $data['object'];
                $new_data['text_lower'] = mb_strtolower($new_data['text']);

                return $new_data;
            });

            print_r($this->initVars);
            $lexicalThis->handleType($type);
        });
    }

    private function handleType(&$type): void
    {
        if (method_exists($this, $type)) $this->$type($this->initVars);
    }

    /**
     * Event message_new --> action 
     */
    protected function handleAction(): void
    {
        $action = $this->data['action'];
        $type = $action['type'];

        if (method_exists($this, $type)) $this->$type($action['member_id']);
    }

    // Действие при приглашении пользователя
    private function chat_invite_user($member_id)
    {
    }

    // Действие при приглашении пользователя по ссылке
    private function chat_invite_user_by_link($member_id)
    {
    }

    // Действие при выходе юзера из беседы\кик
    private function chat_kick_user($member_id)
    {
    }

    // Действие при обновлении фотки беседы
    private function chat_photo_update($member_id)
    {
    }

    // Действие при удалении фото беседы
    private function chat_photo_remove($member_id)
    {
    }

    // Действие при закреплении сообщения
    private function chat_pin_message($member_id)
    {
    }

    // Действие при откреплении
    private function chat_unpin_message()
    {
    }

    /**
     * Event message_new
     */
    public function message_new($data): void
    {
        print_r($this->initVars);
        if ($data['text'] == 'hi') {
            $this->say('Hello nigger');
        }

        if (isset($data['action'])) $this->handleAction();
    }

    /**
     * Event message_event
     */
    public function message_event($data): void
    {
    }
}

$vk = ChatManager::create('access_token', 5.126)->run();

var_dump($vk);
