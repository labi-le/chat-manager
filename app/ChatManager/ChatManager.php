<?php

namespace ChatManager;

use ChatManager\Controller\Controller;
use ChatManager\Controller\TypeController;
use ChatManager\Models\Bot;
use Exception;

class ChatManager
{
    public static function run($type = 'longpool')
    {
        $file = self::configFile();
        isset($type) ?: $file->type;

        $bot = self::auth();

        if ($type == 'longpool') {
            PHP_OS == 'linux' ? $bot->isMultiThread(true) : $bot->isMultiThread(false);

            $bot->listen(function () use ($bot) {
                $data = $bot->parse();
                Controller::handle($data['type'], $data, $bot);
            });
        } elseif ($type == 'callback') {
            $confirm_key = $file->confirmation_key ?? throw new Exception('Confirmation key not found');
            $bot->setConfirm($confirm_key);
            $data = $bot->parse();

            Controller::handle($data['type'], $data, $bot);

        } else throw new Exception('Не указан способ работы бота в config.json (longpool\callback)');

    }

    public static function auth()
    {
        $file = self::configFile();
        $auth = $file->auth ?? throw new Exception('Auth data not found');
                $file->type ?? throw new Exception('Type not found');

        return Bot::create($auth->token, $auth->v);

    }

    public static function configFile()
    {
        $file = './config.json';
        return $file = file_get_contents($file) ? json_decode(file_get_contents($file)) : throw new Exception('config.json не найден');
    }
}