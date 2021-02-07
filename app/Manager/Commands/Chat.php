<?php


namespace Manager\Commands;


use Exception;
use Manager\Models\Utils;

trait Chat
{

    /**
     * Зарегистрировать чат
     */
    public function chatRegistration()
    {
        try {
            $this->vk->isAdmin(-$this->vk->getVars('group_id'), $this->vk->getVars('peer_id'));
        } catch (Exception $e) {
            if ($e->getCode() === 0) $this->vk->reply('Ты меня обманул!!!');
            return;
        }

        $this->db->createChatRecord($this->vk->getVars('chat_id'))
            ? $this->vk->reply('верю-верю') : $this->vk->reply('А мы раньше где-то встречались?');
    }

    /**
     * Показать все настройки
     */
    public function snowAllSettings()
    {
        $text = null;
        foreach ($this->db->snowAllSettings() as $setting => $option)
            $text .= $option['description'] . ' ' . Utils::boolToSmile($option['status']) . PHP_EOL;
        $this->vk->reply($text);
    }

    /**
     * Отправить каллбек кнопки с настройками с возможностью их переключать
     */
    public function sendCallbackSettings()
    {
        $button = null;
        $i = 0;
        foreach ($this->db->snowAllSettings() as $setting => $option) {
            $button[$i][] = $this->vk->buttonCallback($option['description'], $option['status'] ? 'green' : 'red');
            $i++;
        }
        $button[$i][] = $this->vk->buttonCallback('⏪', 'white', ['gui_settings' => 'info']);
        $button[$i][] = $this->vk->buttonCallback('⏩', 'white', ['gui_settings' => 'info']);

        Utils::var_dumpToStdout($button);

        $this->vk
            ->msg('🔧 Gui Settings')
            ->kbd($button, true)
            ->send();
    }
    //TODO Написать изменение настроек гуи
    //TODO написать регулярку для варна за ссылки
    //TODO написать хранилище для спам слов
}