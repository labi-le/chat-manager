<?php


namespace Manager\Models;


interface IChatActions
{
    /**
     * Показать приветственное сообщение
     */
    public function showWelcomeMessage(): string|false;

    /**
     * Показать сообщение при выходе
     */
    public function showExitMessage(): string|false;

    /**
     * Показать запретные слова
     */
    public function showForbiddenWords(): string|false;

    /**
     * Получить все настройки
     */
    public function showAllSettings(): array;

    /**
     * Получить статус настройки
     */
    public function statusSettings(string $setting): int|string|array;

    /**
     * Установить сообщение которое показывается после выхода
     */
    public function setActionUserLeave(int $action): bool;

    /**
     * Установить сообщение которое показывается новому участнику
     */
    public function setActionWelcomeMessage(int $action): bool;

    /**
     * Назначить действие для стикер
     */
    public function setActionSticker(int $action): bool;

    /**
     * Назначить действие для запрещенных слов
     */
    public function setActionForbiddenWords(int $action): bool;

    /**
     * Назначить действие для url
     */
    public function setActionUrl(int $action): bool;

    /**
     * Назначить действие для голосовых сообщений
     */
    public function setActionVoiceMessage(int $action): bool;

    /**
     * Добавить пользователя в список вышедших
     */
    public function addExited(int $member): bool;

    /**
     * Добавить пользователя в бан
     */
    public function addBan(int $member, int $expires, string $reason): bool;

    /**
     * Добавить пользователя в бан
     */
    public function unBan(int $member): bool;

    /**
     * Дать мут
     */
    public function addMute(int $member, int $expires, string $reason): bool;

    /**
     * Снять мут
     */
    public function unMute(int $member): bool;

    /**
     * Дать варн
     */
    public function addWarn(int $member): bool;

    /**
     * Снять варн
     */
    public function unWarn(int $member): bool;
}