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
        var_dump($vars);
        $this->print($vars);
    }


    private function print($data)
    {
        $this->vk->msg(print_r($data, true))->send();
    }
}