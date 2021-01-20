<?php

namespace ChatManager\Commands;

/**
 * Трейт для команд подходящих под категорию чат пидорства и блядства
 * @package labile\bot
 */
trait Manager
{
    /**
     * Кикнуть пользователя
     */
    public function kick()
    {
        $this->vk->msg(print_r($this->vk->getVars(), true))->send();
    }

    //todo написать команды для чм
}