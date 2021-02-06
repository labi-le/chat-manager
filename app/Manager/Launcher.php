<?php

namespace Manager;

use Exception;
use Manager\Controller\Controller;
use Manager\Models\Callback;
use Manager\Models\LongPoll;

class Launcher
{
    private static string $configFile = './config.json';

    public static function run($type = null)
    {
        self::checkPhpVersion();

        $config = self::getConfigFile();
        $auth = $config->auth;

        $type = $type ?? $config->type;


        if ($type == 'longpool') {
            $bot = LongPoll::create($auth->token, $auth->v);
            PHP_OS == 'linux' ? $bot->isMultiThread(true) : $bot->isMultiThread(false);
            $bot->listen(function () use ($bot) {
                $bot->parse();
                Controller::handle($bot->getVars(), $bot);
            });
        } elseif ($type == 'callback') {
            $confirm_key = $auth->confirmation ?? throw new Exception('Confirmation key not found');
            $bot = Callback::create($auth->token, $auth->v)->setConfirm($confirm_key);
            $bot->parse();

            Controller::handle($bot->getVars(), $bot);

        }

    }

    private static function checkConfigFile($file)
    {
        $file ?? throw new Exception('config.json не найден');
        $file->auth ?? throw new Exception('Auth data not found');
        $file->type ?? throw new Exception('Type not found');
    }

    private static function configFile()
    {
        return json_decode(file_get_contents(self::$configFile));
    }

    public static function checkPhpVersion()
    {
        PHP_MAJOR_VERSION >= 8 ?: die('Версия PHP ниже 8, обновляйся');
    }

    private static function getConfigFile()
    {
        $config = self::configFile();
        self::checkConfigFile($config);
        return $config;
    }

    public static function database()
    {
        return self::getConfigFile()->auth->database ?? throw new Exception('Database not found');
    }
}