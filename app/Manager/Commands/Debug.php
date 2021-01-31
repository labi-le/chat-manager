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
        $vars = $this->vk->getVars();
        $this->print($vars);
    }


    private function print($data)
    {
        var_dump($data);
        $this->vk->msg(print_r($data, true))->send();
    }
}