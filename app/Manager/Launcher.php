<?php

namespace Manager;

use DigitalStars\SimpleVK\LongPoll;
use DigitalStars\SimpleVK\SimpleVK;
use DigitalStars\SimpleVK\SimpleVkException;
use Manager\Controller\Controller;
use Manager\Models\ConfigFile;
use Manager\Models\SimpleVKExtend;

class Launcher
{
    private $file;

    private function __construct(string $path)
    {
        $this->checkPhpVersion();
        $this->file = ConfigFile::open($path);

    }

    private function checkPhpVersion()
    {
        PHP_MAJOR_VERSION >= 8 ?: die('Версия PHP ниже 8, обновляйся');
    }

    public static function setConfigFile(string $path): Launcher
    {
        return new self($path);
    }

    public function run(): void
    {
        $this->checkPhpVersion();
        $config = $this->file;
        if ($config['logging_error'] === false) SimpleVkException::disableWriteError();

        $auth = $config['auth'];
        $type = $config['type'];
        if ($type == 'callback')
            $this->callback($auth);
        elseif ($type == 'longpoll')
            $this->longpoll($auth);

    }

    private function callback(array $auth): void
    {
        $bot = SimpleVK::create($auth['token'], $auth['v'])->setConfirm($auth['confirmation']);
        if ($auth['secret'] != false)
            $bot->setSecret($auth['secret']);

        SimpleVKExtend::parse($bot);
        Controller::handle(SimpleVKExtend::getVars(), $bot);
    }

    private function longpoll(array $auth): void
    {
        $bot = LongPoll::create($auth['token'], $auth['v']);
        $bot->listen(function () use ($bot) {
            SimpleVKExtend::parse($bot);
            Controller::handle(SimpleVKExtend::getVars(), $bot);
        });
    }
}