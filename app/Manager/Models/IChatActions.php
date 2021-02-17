<?php


namespace Manager\Models;


interface IChatActions
{
    /**
     * Показать приветственное сообщение
     * @return string|false
     */
    public function showWelcomeMessage(): string|false;

    /**
     * Показать сообщение при выходе
     * @return string|false
     */
    public function showExitMessage(): string|false;

    /**
     * Показать запретные слова
     * @return string|false
     */
    public function showForbiddenWords(): string|false;

    /**
     * Получить все настройки
     * @return array
     */
    public function showAllSettings(): array;

    /**
     * Получить статус настройки
     * @param string $setting
     * @return int|string|array
     */
    public function statusSettings(string $setting): int|string|array;

    /**
     * Установить сообщение которое показывается после выхода
     * @param int $action
     * @return bool
     */
    public function setActionUserLeave(int $action): bool;

    /**
     * Установить сообщение которое показывается новому участнику
     * @param int $action
     * @return bool
     */
    public function setActionWelcomeMessage(int $action): bool;

    /**
     * Назначить действие для стикер
     * @param int $action
     * @return bool
     */
    public function setActionSticker(int $action): bool;

    /**
     * Назначить действие для запрещенных слов
     * @param int $action
     * @return bool
     */
    public function setActionForbiddenWords(int $action): bool;

    /**
     * Назначить действие для url
     * @param int $action
     * @return bool
     */
    public function setActionUrl(int $action): bool;

    /**
     * Назначить действие для голосовых сообщений
     * @param int $action
     * @return bool
     */
    public function setActionVoiceMessage(int $action): bool;

    /**
     * Добавить пользователя в список вышедших
     * @param int $member
     * @return bool
     */
    public function addExited(int $member): bool;

    /**
     * Добавить пользователя в бан
     * @param int $member
     * @param int $expires
     * @param string $reason
     * @return bool
     */
    public function addBan(int $member, int $expires, string $reason): bool;

    /**
     * Добавить пользователя в бан
     * @param int $member
     * @return bool
     */
    public function unBan(int $member): bool;

    /**
     * Дать мут
     * @param int $member
     * @param int $expires
     * @param string $reason
     * @return bool
     */
    public function addMute(int $member, int $expires, string $reason): bool;

    /**
     * Снять мут
     * @param int $member
     * @return bool
     */
    public function unMute(int $member): bool;

    /**
     * Дать варн
     * @param int $member
     * @return bool
     */
    public function addWarn(int $member): bool;

    /**
     * Снять варн
     * @param int $member
     * @return bool
     */
    public function unWarn(int $member): bool;
}