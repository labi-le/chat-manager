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
                'method' => ['isChat', 'isAdmin', 'guiSettingsOffset']
            ],


            [
                'text' => ['[|скажи', '[|повтори', '[|say'],
                'method' => ['isChat', 'say']
            ],

        ];
    }

    /**
     * Массив с payload (нажатие на кнопку)
     * первый ключ - категория команды
     * payload - команда из категории
     * method - какой метод должен выполняться
     * type - тип кнопки (callback или default)
     */
    public static function payload(): array
    {
        return [

            'command' => [
                [
                    'payload' => 'not_supported_button',
                    'method' => ['not_supported_button'],
                    'type' => 'callback'
                ]
            ],

            'settings' =>
                [

                ],

            'gui_settings' =>
                [
                    [
                        'payload' => 'separate_action',
                        'method' => ['eventNoAccess', 'guiSetOptions'],
                        'type' => 'callback'
                    ],

                    [
                        'payload' => 'next',
                        'method' => ['eventNoAccess', 'guiSettingsOffset'],
                        'type' => 'callback'
                    ],

                    [
                        'payload' => 'back',
                        'method' => ['eventNoAccess', 'guiSettingsOffset'],
                        'type' => 'callback'
                    ]
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