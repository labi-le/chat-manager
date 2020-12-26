<?php

namespace labile\bot;

trait Commands
{
    public function list()
    {
        return [
            [
                'text' => ['|привет'],
                'method' => ['_foobar']
            ],

            [
                'text' => ['[|котика', '[|котиков', '[|кот'],
                'method' => ['_cat']
            ],

            [
                'text' => ['кнопочки'],
                'method' => ['_keyboard']
            ],

            [
                'text' => ['[|say'],
                'method' => ['_say']
            ],

            [
                'text' => ['hi'],
                'method' => ['_hiMessage']
            ],

        ];
    }

    protected function _hiMessage()
    {
        $this->msg('Hi ~man~')->send();
    }

    /*
     * Котиков int
     * return котики
     */
    protected function _cat()
    {
        $count = intval(Utils::textWithoutPrefix($this->getVars()['text_lower']));

        if ($count > 10 or $count <= 0) {
            $this->msg("Отинь многа котикаф либо их ваще нет!!!")->send();
        } else {

            $cat = [];
            $smile = \str_repeat('🐈', $count);

            for ($i = 0; $i < $count; $i++) {
                $cat[] = json_decode(file_get_contents('https://aws.random.cat/meow'));
            }

            $this->msg($smile)->img($cat)->send();
        }
    }

    protected function _keyboard()
    {
        $kb[] = $this->buttonText('1', 'white', null);
        $kb[] = $this->buttonText('2', 'red', null);
        $kb[] = $this->buttonText('3', 'blue', null);

        $this->msg('you popal to gay pride')->kbd([$kb], true)->send();
    }

    protected function вагина()
    {
        $array_vagina = [
            'Сидел я в МДК', 'листал картинки про кота~', 'И был доволен всем',
            'Но вдруг стряслась беда', 'Опухли два яйца', 'Мне нужно трахаца',
            'Но у меня прыщи', 'Как классно, что есть ты!', 'Ведь ты! Ты лежала в магазине',
            'Между членом и вагиной', 'И меня к тебе манило', 'Твоё тело из резины',
            'Полные глаза любви', 'И широко раскрытый рот', 'Девушка моей мечты',
            'Плачу лишь раз - всю жизнь дает!', 'В ПИЗДУ И В РОТ!', 'Плачу лишь раз - всю жизнь дает!',
            'Йе - йе - йе...'
        ];

        foreach($array_vagina as $word){
            $this->msg($word)->send();
            sleep(1);
        }
    }

    protected function _say()
    {
        $this->msg(Utils::textWithoutPrefix($this->getVars()['text_lower']))->send();
    }

    protected function heyo()
    {
        $this->msg('Heyooo')->send();
    }

    protected function _foobar()
    {
        $this->msg('foobar')->send();
    }
}
