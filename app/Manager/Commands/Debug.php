<?php

namespace Manager\Commands;

/**
 * Trait Debug
 * @package Manager\Commands
 */
trait Debug
{
    public function vars()
    {
        $this->print('~full~');
        $this->print('~fn~');
        $this->print('~ln~');
        $this->print('~!full~');
    }


    private function print($data)
    {
        var_dump($data);
        $this->vk->msg(print_r($data, true))->send();
    }
}