<?php


namespace ChatManager\Commands;

/**
 * Trait Debug
 * @package ChatManager\Commands
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