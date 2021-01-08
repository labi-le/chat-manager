<?php

namespace labile\bot;

trait Manager
{
    /**
     * Кикнуть пользователя
     * @param int|null $id
     */
    public function kick($id = null)
    {
        $this->msg('бутовски')->send();
    }

    //todo написать команды для чм
}