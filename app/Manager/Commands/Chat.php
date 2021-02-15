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

                if ($setting === ChatsQuery::ACTION) $text['action'] .= $value['description'] . "\nДействие - " . Utils::intToStringAction($value['action']) . PHP_EOL . PHP_EOL;
                if ($setting === ChatsQuery::PENALTY) $text['penalty'] .= $value['description'] . ' - ' . $default . "\nВ случае нарушения - " . Utils::intToStringAction($value['action']) . PHP_EOL . PHP_EOL;
                if ($setting === ChatsQuery::SPECIFIC) $text['specific'] .= $value['description'] . ' - ' . $default . "\nВ случае нарушения - " . Utils::intToStringAction($value['action']) . PHP_EOL . PHP_EOL;
            }
        }
        $this->print(implode("\n", $text));
//        $text .= $settings['mute']['description'] . ': ' . $settings['mute']['default'] . PHP_EOL;

    }

    public function guiSettingsOffset($offset = 0)
    {
        $offset = $this->vk->getVars('payload')['gui_settings']['offset'] ?? $offset;

        $message = $this->vk
            ->msg('🔧 Callback Settings')
            ->kbd($this->sendCallbackSettings($offset), true);

        Utils::var_dumpToStdout($this->sendCallbackSettings($offset));
        $this->vk->getVars('type') == 'message_new'
            ? $message->send()
            : $message->sendEdit($this->vk->getVars('peer_id'), null, $this->vk->getVars('message_id'));


//            ->send();
//        Utils::var_dumpToStdout(212221122121);

    }

    /**
     * Отправить каллбек кнопки с настройками с возможностью их переключать
     * @param int $offset
     * @return array
     */
    private function sendCallbackSettings(int $offset): array
    {
        $button = null;
        $i = 0;
        foreach ($this->db->showAllSettings() as $category => $actions) {
            foreach ($actions as $action => $setting) {
                $button[$i][] = $this->vk->buttonCallback($setting['description'], $setting['action'] ? 'green' : 'red',
                    [
                        'gui_settings' =>
                            [
                                'action' => $action
                            ]
                    ]);
                $i++;
            }
        }

        $button = array_splice($button, $offset, 5);
        if ($offset > 0) $button[6666][] = $this->vk->buttonCallback('Back', 'white',
            [
                'gui_settings' =>
                    [
                        'action' => 'back',
                        'offset' => $offset - 5
                    ]
            ]);

        if ($offset >= 0 and count($button) >= $offset) $button[6666][] = $this->vk->buttonCallback('Next', 'white',
            [
                'gui_settings' =>
                    [
                        'action' => 'next',
                        'offset' => $offset + 5
                    ]
            ]);

        return $button;
//        Utils::var_dumpToStdout($button);
//        $this->vk
//            ->msg('🔧 Callback Settings')
//            ->kbd($button, true)
//            ->send();

    }
    //TODO Написать изменение настроек гуи
    //TODO написать регулярку для варна за ссылки
    //TODO написать хранилище для спам слов
}