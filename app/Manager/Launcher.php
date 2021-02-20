<?php

namespace Manager;

use DigitalStars\SimpleVK\LongPoll;
use DigitalStars\SimpleVK\SimpleVK;
use DigitalStars\SimpleVK\SimpleVkException;
use Exception;
use Manager\Controller\Controller;
use Manager\Models\SimpleVKExtend;

class Launcher
{
    private static string $configFile = './config.json';

    public static function run(): void
    {
        self::checkPhpVersion();
        $config = self::openFile();

        if ($config->logging_error === false) SimpleVkException::disableWriteError();

        $auth = $config->auth;
        $type = $config->type;

        if ($type == 'longpoll') {
            $bot = LongPoll::create($auth->token, $auth->v);
            $bot->listen(function () use ($bot) {
                SimpleVKExtend::parse($bot);
                Controller::handle(SimpleVKExtend::getVars(), $bot);
            });

        } elseif ($type == 'callback') {
            $bot = SimpleVK::create($auth->token, $auth->v)->setConfirm($auth->confirmation);
            if ($auth->secret != false)
                $bot->setSecret($auth->secret);

            SimpleVKExtend::parse($bot);
            Controller::handle(SimpleVKExtend::getVars(), $bot);

        }

    }

    public static function checkPhpVersion()
    {
        PHP_MAJOR_VERSION >= 8 ?: die('Версия PHP ниже 8, обновляйся');
    }

    private static function openFile()
    {
        $config = self::configFile();
        self::checkConfigFile($config);
        return $config;
    }

    private static function configFile()
    {
        return json_decode(file_get_contents(self::$configFile));
    }

    private static function checkConfigFile($file)
    {
        $file ?? throw new Exception('config.json не найден');
        $file->auth ?? throw new Exception('Auth data not found');

        switch ($file->type) {
            case 'callback' :
                $file->auth->confirmation ?? throw new Exception('Не указан confirmation');
                $file->auth->secret ?? throw new Exception('Не указан secret, если не используется поставь false');
                break;

            case 'longpoll':
                break;

            default:
                throw new Exception('Не указан тип работы бота');
        }
    }

}