<?php

namespace Manager\Models;

class ChatsQuery extends QueryBuilder implements IChatSettings
{
    protected string $table = 'chats';
    /**
     * Константы содержащие названия колонок в бд
     */


    /**
     * Название идентификатора
     */
    const ID = 'id';

    /**
     * Идентификатор статуса, добавляется в конце возможной настройки,
     * Можно без дефиса
     */
    const STATUS = '-status';
    /**
     * Текст привественного сообщения
     * Переключатель для прив сообщения
     */

    const WELCOME_MESSAGE_TEXT = 'welcome_text';
    const WELCOME_MESSAGE_STATUS = 'welcome_text' . self::STATUS;

    const EXIT_MESSAGE_TEXT = 'exit_text';
    const EXIT_MESSAGE_STATUS = 'exit_text' . self::STATUS;

    /**
     * Выдача варнов за ссылки в чате
     * Переключатель
     */
    const URL_WARN_STATUS = 'url_warn' . self::STATUS;

    /**
     * Большая коллекция запрещенных слов
     * И его переключатель
     */
    const FORBIDDEN_WORDS = 'forbidden_words';
    const FORBIDDEN_WORDS_STATUS = 'forbidden_words' . self::STATUS;

    /**
     * Переключатель автокика
     */
    const AUTO_KICK_STATUS = 'auto_kick' . self::STATUS;

    /**
     * Создать таблицу
     * @return bool
     */
    public function createChatTable(): bool
    {
        $sql = "CREATE TABLE `$this->table`(
                `" . self::ID . "` INT(11) NOT NULL UNIQUE,
                `" . self::WELCOME_MESSAGE_TEXT . "` TEXT NOT NULL,
                `" . self::WELCOME_MESSAGE_STATUS . "` TINYINT(1) NOT NULL,
                `" . self::EXIT_MESSAGE_TEXT . "` TEXT NOT NULL,
                `" . self::EXIT_MESSAGE_STATUS . "` TINYINT(1) NOT NULL,
                `" . self::URL_WARN_STATUS . "` TINYINT(1) NOT NULL,
                `" . self::FORBIDDEN_WORDS . "` TEXT NOT NULL,
                `" . self::FORBIDDEN_WORDS_STATUS . "` TINYINT(1) NOT NULL,
                `" . self::AUTO_KICK_STATUS . "` TINYINT(1) NOT NULL)";

//        var_dump($sql);
        return (bool)$this->db->query($sql);
    }


    /**
     * Создать новую запись в таблице
     * @param $id
     * @return bool
     */
    public function createChatRecord($id): bool
    {
        try {
            return $this->createRecord(
                [
                    self::ID => $id,
                    self::WELCOME_MESSAGE_TEXT => 0, //выключено
                    self::WELCOME_MESSAGE_STATUS => 0,
                    self::EXIT_MESSAGE_TEXT => 0,
                    self::EXIT_MESSAGE_STATUS => 0,
                    self::AUTO_KICK_STATUS => 0,
                    self::FORBIDDEN_WORDS => 0,
                    self::FORBIDDEN_WORDS_STATUS => 0,
                    self::URL_WARN_STATUS => 0,
                    //etc..
                ]);
        } catch (\Exception){
            return false;
        }
    }

    /**
     * Получить все настройки с бд
     * @return bool|array
     */
    public function snowAllSettings(): null|array
    {
        $status = null;
        $count_status = mb_strlen(self::STATUS);
        foreach ($this->getRecord($this->id) as $setting => $value) {
            if (mb_substr($setting, -$count_status) == self::STATUS)
                $status[$setting] = (bool)$value;
        }
        return $status;
    }

    /**
     * Switch true\false and save
     * @param string $settings_name
     * @return bool
     */
    protected function switchStatus(string $settings_name): bool
    {
        $status = $this->snowAllSettings()[$settings_name];
        if (is_null($status)) return false;

        $sql = "UPDATE $this->table SET $settings_name = !$status WHERE " . self::ID . " = $this->id";
        return (bool)$this->query($sql);
    }

    public function snowWelcomeMessage(): string|bool
    {
        return $this->getRecord($this->id)[self::WELCOME_MESSAGE_TEXT];
    }

    public function snowExitMessage(): string|bool|null
    {
        return $this->getRecord($this->id)[self::EXIT_MESSAGE_TEXT];
    }

    public function snowForbiddenWords(): string|bool|null
    {
        return $this->getRecord($this->id)[self::FORBIDDEN_WORDS];
    }

    public function statusForbiddenWords(): bool
    {
        return $this->getRecord($this->id)[self::FORBIDDEN_WORDS_STATUS];
    }

    public function statusWelcomeMessage(): bool
    {
        return $this->getRecord($this->id)[self::WELCOME_MESSAGE_STATUS];
    }

    public function statusExitMessage(): bool
    {
        return $this->getRecord($this->id)[self::EXIT_MESSAGE_STATUS];
    }

    public function statusUrlWarn(): bool
    {
        return $this->getRecord($this->id)[self::URL_WARN_STATUS];
    }

    public function statusAutoKick(): bool
    {
        return $this->getRecord($this->id)[self::AUTO_KICK_STATUS];
    }

    public function setWelcomeMessage(string $text): bool
    {
        return $this->switchStatus(self::WELCOME_MESSAGE_STATUS);
    }

    public function setExitMessage(string $text): bool
    {
        return $this->switchStatus(self::EXIT_MESSAGE_STATUS);
    }

    public function switchForbiddenWords(): bool|null
    {
        return $this->switchStatus(self::FORBIDDEN_WORDS_STATUS);
    }

    public function switchWelcomeMessage(): bool|null
    {
        return $this->switchStatus(self::WELCOME_MESSAGE_STATUS);
    }

    public function switchExitMessage(): bool|null
    {
        return $this->switchStatus(self::EXIT_MESSAGE_STATUS);
    }

    public function switchUrlWarn(): bool|null
    {
        return $this->switchStatus(self::URL_WARN_STATUS);
    }

    public function switchAutoKick(): bool|null
    {
        return $this->switchStatus(self::AUTO_KICK_STATUS);
    }
}