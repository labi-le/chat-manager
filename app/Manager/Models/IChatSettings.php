<?php


namespace Manager\Models;


interface IChatSettings
{
    /**
     * Показать приветственное сообщение
     * @return string|bool|null
     */
    public function snowWelcomeMessage(): string|bool|null;

    /**
     * Показать сообщение при выходе
     * @return string|bool|null
     */
    public function snowExitMessage(): string|bool|null;

    /**
     * Показать запретные слова
     * @return string|bool|null
     */
    public function snowForbiddenWords(): string|bool|null;

    /*
     * Статусы (что-то типа выкл вкл)
     * Вернут булев или null если ячейки вообще нет
     */
    public function statusForbiddenWords(): bool|null;
    public function statusWelcomeMessage(): bool|null;
    public function statusExitMessage(): bool|null;
    public function statusUrlWarn(): bool|null;
    public function statusAutoKick(): bool|null;

    /*
     * Сеттеры и переключатели
     */

    /**
     * Любой текст без спецсимволов (зальго, etc...)
     * @param string $text
     * @return bool
     */
    public function setWelcomeMessage(string $text):bool;
    public function setExitMessage(string $text):bool;

    /*
     * Общая кнопка (для красоты)
     * Реализован в классе
     * string $settings_name
     * @return bool
     * protected function switchStatus(string $settings_name):bool;
     */

    public function switchForbiddenWords(): bool|null;
    public function switchWelcomeMessage(): bool|null;
    public function switchExitMessage(): bool|null;
    public function switchUrlWarn(): bool|null;
    public function switchAutoKick(): bool|null;
}