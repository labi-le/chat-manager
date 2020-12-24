<?php

namespace labile\bot;

trait Commands
{
    public function list()
    {
        return [
            [
                'text' => ['foo', 'bar', 'foobar'],
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