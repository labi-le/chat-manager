<?php

namespace ChatManager;

use ChatManager\Controller\Controller;
use ChatManager\Controller\TypeController;

class Bot extends Controller
{
    public static function run($type, $bot)
    {
        //todo написать стартовый метод
        $file = self::configFile();
        $auth = $file->auth;
    }

    public static function configFile()
    {
        $file = file_get_contents('./../../config.json');
        return json_decode($file) ?? throw new \Exception('Не удалось открыть файл config.json');
    }
}