<?php

namespace Manager\Commands;

/**
 * Класс для получения списка команд и пэйлоадов
 * Class CommandList
 * @package ChatManager\Commands
 */
final class CommandList
{
    /**
     * Массив с командами
     * [| - начинается с
     * | - похоже на
     * {} - содержит
     * |] - заканчивается на
     * @return array
     */
    public static function text(): array
    {
        return [

            [
                'text' => ['[|котика', '[|котиков', '[|кот'],
                'method' => ['cat']
            ],

            [
                'text' => ['кончить', 'кон4ить'],
                'method' => ['kon4']
            ],

            [
                'text' => ['/chat_reg'],
                'method' => ['isChat', 'chatRegistration']
            ],

            [
                'text' => ['[|варс', '[|варсы', '[|pr', '[|print'],
                'method' => ['vars']
            ],

            [
                'text' => ['|едрен батон', 'блин', 'капец', 'блять', 'пиздец', 'ебать',
                    '|елки иголки', '|екарный бабай', '|бляха муха', '|твою дивизию'],
                'method' => ['isChat', 'blin']
            ],

            [
                'text' => ['[|кик', '[|kick', '[|выгнать', '[|кикнуть', '[|ремув', '[|убрать'],
                'method' => ['isChat', 'isAdmin', 'kick']
            ],

            [
                'text' => ['меню', 'настройки'],
                'method' => ['isChat', 'isAdmin', 'snowAllSettings']
            ],

            [
                'text' => ['gui'],
                'method' => ['isChat', 'isAdmin', 'sendCallbackSettings']
            ],


            [
                'text' => ['[|скажи', '[|повтори', '[|say'],
                'method' => ['isChat', 'say']
            ],

        ];
    }

    /**
     * Массив с payload (нажатие на кнопку)
     * @return array
     */
    public static function payload(): array
    {
        return [

            'command' => [
                [
                    'key' => 'not_supported_button',
                    'method' => ['_not_supported_button']
                ]
            ],

            'settings' =>
                [
                    [
                        'payload' => 'exit_msg',
                        'method' => ['_eventCheckAdmin', '_chatSwitcher']
                    ],

                    [
                        'payload' => 'welcome_msg',
                        'method' => ['_eventCheckAdmin', '_chatSwitcher']
                    ],

                    [
                        'payload' => 'rules',
                        'method' => ['_eventCheckAdmin', '_chatSwitcher']
                    ],

                    [
                        'payload' => 'auto_kick',
                        'method' => ['_eventCheckAdmin', '_chatSwitcher']
                    ],

                ],

            'chat' =>
                [
                    [
                        'payload' => 'registration',
                        'method' => ['chatRegistration'],
                        'type' => 'default'
                    ],

                ],

        ];

    }
}