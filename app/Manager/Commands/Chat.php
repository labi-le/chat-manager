<?php


namespace Manager\Commands;


use Exception;
use Manager\Models\ChatsQuery;
use Manager\Models\SimpleVKExtend;
use Manager\Models\Utils;

trait Chat
{

    /**
     * Зарегистрировать чат
     */
    public function chatRegistration()
    {
        try {
            $this->vk->isAdmin(-SimpleVKExtend::getVars('group_id'), SimpleVKExtend::getVars('peer_id'));
        } catch (Exception $e) {
            if ($e->getCode() === 0) $this->vk->reply('Ты меня обманул!!!');
            return;
        }

        $this->db->createChatRecord(SimpleVKExtend::getVars('chat_id'))
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

                if ($setting === ChatsQuery::ACTION) $text['action'] .= $value['description'] . "\nДействие - " . ChatsQuery::intToStringAction($value['action']) . PHP_EOL . PHP_EOL;
                if ($setting === ChatsQuery::PENALTY) $text['penalty'] .= $value['description'] . ' - ' . $default . "\nВ случае нарушения - " . ChatsQuery::intToStringAction($value['action']) . PHP_EOL . PHP_EOL;
                if ($setting === ChatsQuery::SPECIFIC) $text['specific'] .= $value['description'] . ' - ' . $default . "\nВ случае нарушения - " . ChatsQuery::intToStringAction($value['action']) . PHP_EOL . PHP_EOL;
            }
        }
        $this->print(implode("\n", $text));

    }

    /**
     * Провалиться в определенный пункт настроек
     */
    public function guiSetOptions()
    {
        $action = SimpleVKExtend::getVars('payload')['gui_settings']['type'];
        $option = $this->db->statusSettings($action);

        $i = 0;
        foreach ($option['allowed_options'] as $allowed) {
            $button[$i][] = $this->vk->buttonCallback(ChatsQuery::intToStringAction($allowed), $option['action'] ? 'green' : 'red',
                [
                    'gui_settings' =>
                        [
                            'action' => 'separate_action',
                            'type' => 2121 . '.' . $action
                        ]
                ]);
            $i++;
        }

        if ($option[ChatsQuery::DEFAULT]) $button[$i][] = $this->vk->buttonCallback('Добавить текст', $option['action'] ? 'green' : 'red',
            [
                'gui_settings' =>
                    [
                        'action' => 'separate_action',
                        'type' => 2121 . '.' . $action
                    ]
            ]);

        $button[2e9][] = $this->vk->buttonCallback('Main menu', 'white',
            [
                'gui_settings' =>
                    [
                        'action' => 'back',
                        'offset' => 0
                    ]
            ]);

        $this->vk
            ->msg($option['description'] . "\n\nВозможные действия:")
            ->kbd($button, true)
            ->sendEdit(SimpleVKExtend::getVars('peer_id'), null, SimpleVKExtend::getVars('message_id'));

    }

    /**
     * Листнуть вперед или назад в sendCallbackSettings
     * @param int $offset
     */
    public function guiSettingsOffset($offset = 0)
    {
        $offset = SimpleVKExtend::getVars('payload')['gui_settings']['offset'] ?? $offset;

        $message = $this->vk
            ->msg('🔧 Callback Settings')
            ->kbd($this->sendCallbackSettings($offset), true);

        SimpleVKExtend::getVars('type') == 'message_new'
            ? $message->send()
            : $message->sendEdit(SimpleVKExtend::getVars('peer_id'), null, SimpleVKExtend::getVars('message_id'));

    }

    /**
     * Отправить каллбек кнопки с настройками с возможностью их переключать
     * @param int $offset
     * @return array
     */
    private function sendCallbackSettings(int $offset): array
    {
        $payload = [
            'gui_settings' =>
                [
                ]
        ];

        $payload['gui_settings']['action'] = 'separate_action';
        $button = $this->generateGui($this->db->showAllSettings(), 'description', $payload);
        $button = array_splice($button, $offset, 5);

        if ($offset > 0)
            $button[2e9][] = $this->addNavigateGuiButton('back', $offset - 5);
        if ($offset >= 0 and count($button) >= $offset)
            $button[2e9][] = $this->addNavigateGuiButton('next', $offset + 5);

        Utils::var_dumpToStdout($button);
        return $button;

    }

    /**
     * Генератор клавы коки сука
     */
    private function generateGui(array $data, string $key, array $payload): array
    {
        $i = 0;
        $button = [];
        foreach ($data as $category => $actions) {
            foreach ($actions as $action => $setting) {
                mb_strlen($setting[$key] > 40) ? $description = mb_substr($setting[$key], 0, 40) : $description = $setting[$key];
                $payload[key($payload)]['type'] = $category . '.' . $action;
                $button[$i][] = $this->vk->buttonCallback($description, 'blue', $payload);
                $i++;
            }
        }
        return $button;
    }

    private function addNavigateGuiButton(string $button_name, int $offset)
    {
        $payload['gui_settings']['action'] = $button_name;
        $payload['gui_settings']['offset'] = $offset;
        return $this->vk->buttonCallback($button_name, 'white', $payload);
    }

//TODO Написать изменение настроек гуи
//TODO написать регулярку для варна за ссылки
//TODO написать хранилище для спам слов
}