<?php

// namespace DigitalStars\SimpleVK;

use DigitalStars\SimpleVK\LongPoll as longpool;
use DigitalStars\SimpleVK\SimpleVK as vk;

require_once('./vendor/digitalstars/simplevk/autoload.php');

class ChatManager extends vk
{
    public function say($text)
    {
        // parent::msg('heyooo')->send();
        return $text;
    }


    public static function create($token, $version, $also_version = null)
    {
        return new self($token, $version, $also_version);
    }

    public function run($token, $version, $also_version = null)
    {
        $lp = longpool::create($token, $version, $also_version);
        $lp->listen(function ($data) use ($lp) {                       // Получение событий из LongPool
            $lp->initVars($id, $user_id, $type, $message, $payload, $msg_id, $attachments);   // Парсинг полученных событий
            if ($type == 'message_new') {                                     // Если событие - новое сообщение
                $lp->msg("Тестовое сообщение")->send();                       // Отправка ответного сообщения
            }
        });
    }

}

$vk = ChatManager::run('access_token', 5.126);

var_dump($vk);
