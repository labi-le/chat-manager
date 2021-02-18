<?php

namespace Manager;

use Exception;
use Manager\Controller\Controller;
use Manager\Models\Callback;
use Manager\Models\LongPoll;

class Launcher
{
    private static string $configFile = './config.json';

    public static function run()
    {
        self::checkPhpVersion();
        $config = self::openFile();
        $auth = $config->auth;
        $type = $config->type;

        if ($type == 'longpoll') {
            $bot = LongPoll::create($auth->token, $auth->v);
            PHP_OS == 'linux' ? $bot->isMultiThread(true) : $bot->isMultiThread(false);
            $bot->listen(function () use ($bot) {
                $bot->parse();
                Controller::handle($bot->getVars(), $bot);
            });

        } elseif ($type == 'callback') {
            $bot = Callback::create($auth->token, $auth->v)->setConfirm($auth->confirmation)->setSecret($auth->secret);

            $bot->parse();
            Controller::handle($bot->getVars(), $bot);

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

            case 'longpoll' :
                if (php_sapi_name() !== "cli")
                    die('Запуск longpoll возможен только в cli режиме');
                break;

            default:
                throw new Exception('Не указан тип работы бота');
        }
    }

}