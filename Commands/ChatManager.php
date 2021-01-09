<?php

namespace labile\bot;

/**
 * Трейт для команд подходящих под категорию чат менеджмента
 * @package labile\bot
 */
trait Manager
{
    /**
     * Кикнуть пользователя
     */
    public function kick()
    {
        $this->vk->msg('бутовски')->send();
    }

    //todo написать команды для чм
}