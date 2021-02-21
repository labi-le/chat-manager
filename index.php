<?php

declare(strict_types=1);

echo 'Ok';

use Manager\Launcher;

require_once('vendor/autoload.php');

/**
 * Путь до конфиг файла
 * Если его нет, просто укажи необходимый путь и файл будет создан
 */
$config = __DIR__ . '/config.json';
Launcher::setConfigFile($config)->run();
