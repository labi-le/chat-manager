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
        $this->print($this->vk->getVars());
    }

    private function print($data)
    {
        $this->vk->msg(print_r($data, true))->send();
    }
}