<?php

use labile\bot\ChatManager;

require_once('./vendor/digitalstars/simplevk/autoload.php');
require_once('./autoload.php');

$vk = ChatManager::create('', 5.126)
    ->isMultiThread(true);  // многопоточить как сука???????? только линукс
$vk->run();