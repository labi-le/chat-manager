<?php

namespace Manager\Controller;

use Manager\Commands\Events;

class ActionController extends Controller
{
    /**
     * обработка action (message\\action)
     * @param array $action
     * @return void
     */
    public static function handleAction(array $action): void
    {
        $type = $action['type'];
        $member_id = $action['member_id'];

        if (method_exists(Events::class, $type)) Events::$type($member_id);
    }
}