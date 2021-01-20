<?php

namespace ChatManager\Commands;

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
                'method' => ['_cat']
            ],

            [
                'text' => ['кончить', 'кон4ить'],
                'method' => ['_kon4']
            ],

            [
                'text' => ['вагина'],
                'method' => ['vagina']
            ],

            [
                'text' => ['блин', 'капец', 'блять', 'пиздец', 'ебать', '|елки иголки', 'екарный бабай'],
                'method' => ['_blin']
            ],

            [
                'text' => ['[|кик', '[|kick', '[|выгнать', '[|кикнуть'],
                'method' => ['isChat', 'isAdmin', 'kick']
            ],

            [
                'text' => ['меню', 'настройки', 'gui', 'menu'],
                'method' => ['isChat', 'isAdmin', 'createGui']
            ],

            [
                'text' => ['pr'],
                'method' => ['pr']
            ],

            [
                'text' => ['[|скажи', '[|повтори', '[|say'],
                'method' => ['say']
            ],

            [
                'text' => ['hi'],
                'method' => ['_hiMessage']
            ],

        ];
    }

    /**
     * Массив с payload (нажатие на кнопку)
     * @return array
     */
    public static function payload(): array
    {
        //todo реализовать команды из массива
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
                        'key' => 'exit_msg',
                        'method' => ['_eventCheckAdmin', '_chatSwitcher']
                    ],

                    [
                        'key' => 'welcome_msg',
                        'method' => ['_eventCheckAdmin', '_chatSwitcher']
                    ],

                    [
                        'key' => 'rules',
                        'method' => ['_eventCheckAdmin', '_chatSwitcher']
                    ],

                    [
                        'key' => 'auto_kick',
                        'method' => ['_eventCheckAdmin', '_chatSwitcher']
                    ],

                ],

            'chat' =>
                [
                    [
                        'key' => 'registration',
                        'method' => ['_chatCreate']
                    ],

                ],

        ];

    }
}