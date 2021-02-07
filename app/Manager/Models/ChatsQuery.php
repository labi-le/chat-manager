<?php


namespace Manager\Models;


class ChatsQuery extends QueryBuilder implements IChatSettings
{

    /**
     * Название идентификатора
     */
    const ID = 'id';
    /**
     * Константы содержащие названия колонок в бд
     */
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

    const EXIT_MESSAGE_TEXT = 'exit_text';

    const MAX_WORDS = 'max_words';
    const MAX_WORDS_COUNT = 2000;

    /**
     * Выдача варнов за ссылки в чате
     * Переключатель
     */
    const URL = 'url';
    /**
     * Большая коллекция запрещенных слов
     * И его переключатель
     */
    const FORBIDDEN_WORDS = 'forbidden_words';
    /**
     * Переключатель автокика
     */
    const AUTO_KICK = 'user_exit';

    const STICKER = 'sticker';
    const VOICE_MESSAGE = 'voice';
    /**
     * Стандартные настройки для базы данных
     * https://sleekdb.github.io/#/configurations
     */
    const CONFIGURATION_DB =
        [
            "auto_cache" => false,
            "cache_lifetime" => null,
            "timeout" => 120,
            "primary_key" => self::ID
        ];

    protected string $store_name = 'chats';

    /**
     * Создать запись из готового генератора
     * @param int|array $params
     * @return bool
     */
    public function createRecord(int|array $params): bool
    {
        return parent::createRecord($this->generateTable($params));
    }

    /**
     * Сгенерировать таблицу
     * @param int $id
     * @return array
     */
    private function generateTable(int $id): array
    {
        return [
            'id' => $id,
            'settings' =>

                [/**
                 * Возможные нарушение и потенциальные наказания
                 */
                    'penalty' =>
                        [
                            /**
                             * 0 - nothing, 1 - warn, 2 - kick, 3 - ban
                             */
                            self::URL => 0,
                            self::STICKER => 0,
                            self::VOICE_MESSAGE => 0,
                            self::FORBIDDEN_WORDS => 0,
                            self::MAX_WORDS => 0
                        ],

                    'status' =>
                        [
                            /**
                             * 0 - no action, 1 - all action ||bool
                             */
                            self::AUTO_KICK => 0,
                            self::EXIT_MESSAGE_TEXT => 0,
                            self::WELCOME_MESSAGE_TEXT => 0

                        ],

                    /**
                     * Список запрещенных слов
                     * ['ddawdwa', 'dwdaawd', 'dwadwadwfe']
                     */
                    'forbidden_words' => [],

                    /**
                     * Дефолтные настройки
                     */
                    'default' =>
                        [
                            'warn' => 3, //кол-во варнов после которых бан
                            'ban' => 3600, //1 hour
                            self::MAX_WORDS => self::MAX_WORDS_COUNT //слов
                        ]
                ],

            'members' =>
                [
                    'exited' => [],
                    'banned' =>
                        [
                            /**
                             * 418618 =>
                             * [
                             * 'time' => 212, //Время бана
                             * 'reason' => 'Причина бана'
                             * ]
                             */

                        ]
                ]
        ];
    }

    /**
     * @inheritDoc
     */
    public function snowWelcomeMessage(): string|bool
    {
        // TODO: Implement snowWelcomeMessage() method.
    }

    /**
     * @inheritDoc
     */
    public function snowExitMessage(): string|bool
    {
        // TODO: Implement snowExitMessage() method.
    }

    /**
     * @inheritDoc
     */
    public function snowForbiddenWords(): string|bool
    {
        // TODO: Implement snowForbiddenWords() method.
    }

    public function statusForbiddenWords(): bool|null
    {
        // TODO: Implement statusForbiddenWords() method.
    }

    public function statusWelcomeMessage(): bool|null
    {
        // TODO: Implement statusWelcomeMessage() method.
    }

    public function statusExitMessage(): bool|null
    {
        // TODO: Implement statusExitMessage() method.
    }

    public function statusUrlWarn(): bool|null
    {
        // TODO: Implement statusUrlWarn() method.
    }

    public function statusAutoKick(): bool|null
    {
        // TODO: Implement statusAutoKick() method.
    }

    /**
     * @inheritDoc
     */
    public function setWelcomeMessage(string $text): bool
    {
        // TODO: Implement setWelcomeMessage() method.
    }

    public function setExitMessage(string $text): bool
    {
        // TODO: Implement setExitMessage() method.
    }

    public function switchForbiddenWords(): bool|null
    {
        // TODO: Implement switchForbiddenWords() method.
    }

    public function switchWelcomeMessage(): bool|null
    {
        // TODO: Implement switchWelcomeMessage() method.
    }

    public function switchExitMessage(): bool|null
    {
        // TODO: Implement switchExitMessage() method.
    }

    public function switchUrlWarn(): bool|null
    {
        // TODO: Implement switchUrlWarn() method.
    }

    public function switchAutoKick(): bool|null
    {
        // TODO: Implement switchAutoKick() method.
    }

    /**
     * @inheritDoc
     */
    public function snowAllSettings(): null|array
    {
        // TODO: Implement snowAllSettings() method.
    }
}