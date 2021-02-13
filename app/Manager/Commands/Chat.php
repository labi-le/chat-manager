<?php


namespace Manager\Commands;


use Exception;
use Manager\Models\ChatsQuery;
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
        $settings = $this->db->showAllSettings();
        $text['action'] = "default:\n";
        $text['penalty'] = "penalty:\n";
        $text['specific'] = "specific:\n";

        foreach ($settings as $setting => $key) {
            foreach ($key as $value) {
                if (!isset($value['default'])) $default = '';
                elseif (is_array($value['default'])) $default = implode(", ", $value['default']);
                else $default = $value['default'];

                if ($setting === ChatsQuery::ACTION) $text['action'] .= $value['description'] . "\nСтатус - " . Utils::intToStringAction($value['action']) . PHP_EOL . PHP_EOL;
                if ($setting === ChatsQuery::PENALTY) $text['penalty'] .= $value['description'] . ' - ' . $default . "\nНаказание - " . Utils::intToStringAction($value['action']) . PHP_EOL . PHP_EOL;
                if ($setting === ChatsQuery::SPECIFIC) $text['specific'] .= $value['description'] . ' - ' . $default . "\nНаказание - " . Utils::intToStringAction($value['action']) . PHP_EOL . PHP_EOL;
            }
        }
        $this->print(implode("\n", $text));
//        $text .= $settings['mute']['description'] . ': ' . $settings['mute']['default'] . PHP_EOL;

    }

    /**
     * Отправить каллбек кнопки с настройками с возможностью их переключать
     */
    public function sendCallbackSettings()
    {
        $button = null;
        $i = 0;
        foreach ($this->db->showAllSettings() as $setting => $option) {
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