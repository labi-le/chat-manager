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
                'text' => ['hi'],
                'method' => ['_hiMessage']
            ],

        ];
    }

    protected function _hiMessage()
    {
        $this->msg('Hi ~man~')->send();
    }

    protected function вагина()
    {
        $this->msg('Сидел я в МДК,')->send();
        $this->msg('листал картинки про кота~')->send();
        $this->msg('И был доволен всем')->send();
        $this->msg('Но вдруг стряслась беда.
Опухли два яйца,
Мне нужно трахаца,
Но у меня прыщи,
Как классно, что есть ты!')->send();
        $this->msg('Ведь ты! Ты лежала в магазине')->send();
        $this->msg('Между членом и вагиной')->send();
        $this->msg('И меня к тебе манило
Твое тело из резины,
Полные глаза любви
И широко раскрытый рот
Девушка моей мечты,
Плачу лишь раз - всю жизнь дает!
В ПИЗДУ И В РОТ!
Плачу лишь раз - всю жизнь дает!
Йе-йе-йе...')->send();
    }

    protected function say($text)
    {
        $this->msg($text)->send();
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