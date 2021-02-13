<?php


namespace Manager\Models;


class ChatsQuery extends QueryBuilder implements IChatActions
{

    /**
     * Название идентификатора
     */
    const ID = 'id';
    /**
     * Константы содержащие названия колонок в бд
     */

    /**
     * Путь до настроек
     */
    const SETTINGS = 'settings';

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
     */
    const URL = 'url';

    /**
     * Большая коллекция запрещенных слов
     */
    const FORBIDDEN_WORDS = 'forbidden_words';

    const USER_LEAVE = 'user_exit';
    const STICKER = 'sticker';
    const VOICE_MESSAGE = 'audio_message';
    const WALL = 'wall';

    const NO_ACTION = 0;
    const WARN_ACTION = 1;
    const KICK_ACTION = 2;
    const BAN_ACTION = 3;
    const SHOW_ACTION = 4;

    const DEFAULT = 'default';
    const ACTION = 'action';
    const SPECIFIC = 'specific';
    const PENALTY = 'penalty';

    const DESCRIPTION = 'description';
    const MEMBERS = 'members';
    const EXITED = 'exited';
    const WARNED = 'warned';
    const MUTED = 'muted';

    const BANNED = 'banned';
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
     * @inheritDoc
     * @param int $action
     * @param string $path
     * @return bool
     */
    public function setActionUserLeave(int $action): bool
    {
        return $this->setAction([self::NO_ACTION, self::BAN_ACTION], $action, self::USER_LEAVE);
    }

    private function setAction(array $allowed_actions, int $action, string $path): bool
    {
        if (in_array($action, $allowed_actions)) {
            $this->data->set(self::SETTINGS . $path . self::ACTION, $action);
            $this->update($this->data);
            return true;
        } else return false;
    }

    /**
     * @inheritDoc
     * @param int $member
     * @param string $path
     * @return bool
     */
    public function addExited(int $member): bool
    {
        $this->data->add(self::MEMBERS . self::EXITED . $member, ['time' => time()]);
        return $this->addTo($this->data);
    }

    /**
     * @inheritDoc
     * @param int $member
     * @param int $expires
     * @param string $reason
     * @param string $path
     * @return bool
     */
    public function addMute(int $member, int $expires, string $reason): bool
    {
        return $this->addBan($member, $expires, $reason, self::MEMBERS . self::MUTED);
    }

    /**
     * @inheritDoc
     * @param int $member
     * @param int $expires
     * @param string $reason
     * @param string $path
     * @return bool
     */
    public function addBan(int $member, int $expires, string $reason, string $path = self::MEMBERS . self::BANNED): bool
    {
        $this->data
            ->add($path . $member,
                [
                    'expires' => time() + $expires,
                    'reason' => $reason
                ]);
        return $this->addTo($this->data);
    }

    /**
     * @inheritDoc
     * @param int $member
     * @param string $path
     * @return bool
     */
    public function addWarn(int $member): bool
    {
        $count = $this->data->get(self::MEMBERS . self::WARNED . $member . 'count');
        $this->data
            ->add(self::MEMBERS . self::WARNED . $member,
                [
                    'count' => (int)$count + 1,
                ]);
        return $this->addTo($this->data);
    }

    /**
     * @inheritDoc
     * @param int $member
     * @param string $path
     * @return bool
     */
    public function unWarn(int $member): bool
    {
        return $this->deleteIn(self::MEMBERS . self::WARNED . $member);
    }

    /**
     * @inheritDoc
     * @param int $member
     * @param string $path
     * @return bool
     */
    public function unMute(int $member): bool
    {
        return $this->deleteIn(self::MEMBERS . self::MUTED . $member);
    }

    /**
     * @inheritDoc
     * @param int $member
     * @param string $path
     * @return bool
     */
    public function unBan(int $member): bool
    {
        return $this->deleteIn(self::MEMBERS . self::BANNED . $member);
    }

    /**
     * @inheritDoc
     */
    public function showWelcomeMessage(): string
    {
        $welcome_message = $this->statusSettings(self::ACTION . self::WELCOME_MESSAGE_TEXT, self::DEFAULT);
        return mb_strlen($welcome_message) ? 'Сообщение не установлено' : $welcome_message;
    }

    /**
     * @inheritDoc
     */
    public function statusSettings(string $setting, string $action): int|string|array
    {
        return $this->data->get(self::SETTINGS . $setting . $action);
    }

    /**
     * @inheritDoc
     */
    public function showExitMessage(): string
    {
        $exit_message = $this->statusSettings(self::ACTION . self::EXIT_MESSAGE_TEXT, self::DEFAULT);
        return mb_strlen($exit_message) ? 'Сообщение не установлено' : $exit_message;

    }

    /**
     * @inheritDoc
     */
    public function showForbiddenWords(): string
    {
        $forbidden_words = $this->statusSettings(self::SPECIFIC . self::FORBIDDEN_WORDS, self::DEFAULT);
        return $forbidden_words === [] ? 'Список запрещенных слов пуст' : implode(', ', $forbidden_words);
    }

    /**
     * @inheritDoc
     */
    public function showAllSettings(): array
    {
        return $this->data->get(self::SETTINGS);
    }

    /**
     * @inheritDoc
     */
    public function setActionWelcomeMessage(int $action): bool
    {
        return $this->setAction([self::NO_ACTION, self::SHOW_ACTION], $action, self::ACTION . self::WELCOME_MESSAGE_TEXT);
    }

    /**
     * @inheritDoc
     */
    public function setActionForbiddenWords(int $action): bool
    {
        return $this->setAction([self::NO_ACTION, self::WARN_ACTION, self::KICK_ACTION, self::BAN_ACTION], $action, self::FORBIDDEN_WORDS);
    }

    /**
     * @inheritDoc
     */
    public function setActionUrl(int $action): bool
    {
        return $this->setAction([self::NO_ACTION, self::WARN_ACTION, self::KICK_ACTION, self::BAN_ACTION], $action, self::ACTION . self::URL);
    }

    /**
     * @inheritDoc
     */
    public function setActionVoiceMessage(int $action): bool
    {
        return $this->setAction([self::NO_ACTION, self::WARN_ACTION, self::KICK_ACTION, self::BAN_ACTION], $action, self::ACTION . self::VOICE_MESSAGE);
    }

    /**
     * @inheritDoc
     */
    public function setActionSticker(int $action): bool
    {
        return $this->setAction([self::NO_ACTION, self::WARN_ACTION, self::KICK_ACTION, self::BAN_ACTION], $action, self::ACTION . self::STICKER);
    }

    /**
     * Сгенерировать таблицу
     * @param int $id
     * @return array
     */
    protected function __generateTable(int $id): array
    {
        return [
            self::ID => $id,
            self::SETTINGS =>

                [

                    /**
                     * Настройки
                     */

                    self::ACTION =>
                        [
                            self::WELCOME_MESSAGE_TEXT =>
                                [
                                    self::DESCRIPTION => 'Приветственное сообщение',
                                    self::DEFAULT => 'Привет!',
                                    self::ACTION => self::NO_ACTION
                                ],

                            self::EXIT_MESSAGE_TEXT =>
                                [
                                    self::DESCRIPTION => 'Сообщение после выхода участника',
                                    self::DEFAULT => 'Пока',
                                    self::ACTION => self::NO_ACTION
                                ],

                            self::USER_LEAVE =>
                                [
                                    self::DESCRIPTION => 'Юзер покинул конференцию',
                                    self::ACTION => self::NO_ACTION
                                ],

                            self::URL =>
                                [
                                    self::DESCRIPTION => 'Юзеру отправил ссылку',
                                    self::ACTION => self::NO_ACTION
                                ],

                            self::STICKER =>
                                [
                                    self::DESCRIPTION => 'Юзеру отправил стикер',
                                    self::ACTION => self::NO_ACTION
                                ],

                            self::WALL =>
                                [
                                    self::DESCRIPTION => 'Юзеру отправил пост',
                                    self::ACTION => self::NO_ACTION
                                ],

                            self::VOICE_MESSAGE =>
                                [
                                    self::DESCRIPTION => 'Юзер отправил голосовое сообщение',
                                    self::ACTION => self::NO_ACTION
                                ],
                        ],

                    self::PENALTY =>
                        [
                            'warn' =>
                                [
                                    self::DESCRIPTION => 'Дефолтное кол-во варнов',
                                    self::DEFAULT => 3,
                                    self::ACTION => self::BAN_ACTION
                                ],

                            'ban' =>
                                [
                                    self::DESCRIPTION => 'Дефолтное время бана',
                                    self::DEFAULT => 3600,
                                    self::ACTION => self::BAN_ACTION
                                ],

                            'add_banned_user' =>
                                [
                                    self::DESCRIPTION => 'Добавил пользователя который находится в бане',
                                    self::ACTION => self::NO_ACTION
                                ],

                            'mute' =>
                                [
                                    self::DESCRIPTION => 'Дефолтное время мута',
                                    self::DEFAULT => 3600,
                                    self::ACTION => self::WARN_ACTION
                                ],

                        ],

                    self::SPECIFIC =>
                        [
                            self::MAX_WORDS =>
                                [
                                    self::DESCRIPTION => 'Лимит слов',
                                    self::DEFAULT => 0,
                                    self::ACTION => self::NO_ACTION
                                ],


                            /**
                             * Список запрещенных слов
                             * ['ddawdwa', 'dwdaawd', 'dwadwadwfe']
                             */
                            self::FORBIDDEN_WORDS =>
                                [
                                    self::DESCRIPTION => 'Список запрещенных слов',
                                    self::DEFAULT => [],
                                    self::ACTION => self::NO_ACTION
                                ],
                        ],
                ],

            self::MEMBERS =>
                [
                    self::EXITED =>
                        [
                            /**
                             * 18618 =>
                             *      [
                             *          'time' => 09876 Время выхода
                             *      ]
                             */
                        ],
                    self::BANNED =>
                        [
                            /**
                             * 418618 =>
                             *      [
                             *          'time' => 987654, //Время бана
                             *          'reason' => 'Причина бана'
                             *      ]
                             */

                        ],
                    self::MUTED =>
                        [
                            /**
                             * 418618 =>
                             *      [
                             *          'time' => 987654, //Конец мута
                             *          'reason' => 'Причина мута'
                             *      ]
                             */

                        ],
                    self::WARNED =>
                        [
                            /**
                             * 418618 =>
                             *      [
                             *          'count' => 0, //кол-во варнов
                             *      ]
                             */

                        ]
                ]
        ];
    }
}