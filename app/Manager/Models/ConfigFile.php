<?php


namespace Manager\Models;


use Exception;

class ConfigFile
{
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


    public static function open(string $file)
    {
        return self::openFile($file);
    }


    /**
     * Открыть файл
     * @param string $path
     * @return mixed
     * @throws Exception
     */
    private static function openFile(string $path): array
    {
        file_exists($path) ?: self::createConfigFile($path);
        $file = file_get_contents($path);
        $file = json_decode($file, true);
        self::validateConfigFile($file);
        return $file;

    }

    /**
     * Создать конфиг файл
     * @param string $file
     */
    private static function createConfigFile(string $file)
    {
        $json = json_encode(self::CONFIG_FILE_STRUCTURE);
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

        if($file['type'] == 'callback') {
            !empty($auth['confirmation']) ?: throw new Exception('Не указан confirmation');
            !is_bool($auth['secret']) and !empty(['auth']['secret']) ?: throw new Exception('Не указан secret, если не используется поставь false');
        }

    }

}