<?php

declare(strict_types=1);


namespace Manager\Models;


use Exception;

class ConfigFile
{
    /**
     * Как выглядит конфиг
     */
    private const CONFIG_FILE_STRUCTURE =
        [
            'auth' =>
                [
                    'token' => '',
                    'v' => 5.126,
                    'confirmation' => '',
                    'secret' => false // bool

                ],

            'logging_error' => true, //bool
            'type' => 'callback' // callback or longpoll
        ];


    /**
     * Открыть файл
     * @param string $file
     * @return array
     * @throws Exception
     */
    public static function open(string $file): array
    {
        return self::openFile($file);
    }


    /**
     * Открыть файл и проверить по шаблону
     * @param string $path
     * @return mixed
     * @throws Exception
     */
    private static function openFile(string $path): array
    {
        file_exists($path) ?: self::createConfigFile($path);
        $file = file_get_contents($path);
        $file = json_decode($file, true, 512, JSON_THROW_ON_ERROR);
        self::validateConfigFile($file);
        return $file;

    }

    /**
     * Создать конфиг файл
     * @param string $file
     */
    private static function createConfigFile(string $file)
    {
        $json = json_encode(self::CONFIG_FILE_STRUCTURE, JSON_THROW_ON_ERROR);
        file_put_contents($file, $json);
    }

    /**
     * Проверить файл на недостающие параметры
     * @param array $file
     * @throws Exception
     */
    private static function validateConfigFile(array $file)
    {
        $file['type'] ?? throw new Exception('Не указан тип работы бота');
        $auth = $file['auth'];
        !empty($auth['token']) ?: throw new Exception('Не указан токен');
        !empty($auth['v']) ?: throw new Exception('Не указана версия API');

        if ($file['type'] === 'callback') {
            !empty($auth['confirmation']) ?: throw new Exception('Не указан confirmation');
            !is_bool($auth['secret']) and !empty(['auth']['secret']) ?: throw new Exception('Не указан secret, если не используется поставь false');
        }

    }

}