<?php

use labile\bot\ChatManager;

require_once('./vendor/digitalstars/simplevk/autoload.php');
require_once('./autoload.php');

$vk = ChatManager::create('44e451b3a9c4f23a7af565be43ec651db7470873fe7ff45bccf1a30156d65a4f94b15be0ed176af07a104', 5.126)
    ->isMultiThread(true);  // многопоточить как сука???????? только линукс
$vk->run();

//$vk = ChatManager::create('7d374ccebc07c239c17a66c45f9f8e7a8041040b6214ecea553f3ce523ab4136c48aa0474ccd723889f93', 5.126)
//    ->isMultiThread(true);  // многопоточить как сука???????? только линукс
//$vk->run();