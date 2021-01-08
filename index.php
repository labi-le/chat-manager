<?php

use labile\bot\ChatManager;

require_once('./vendor/digitalstars/simplevk/autoload.php');
require_once('autoload.php');

//longpool
$bot = ChatManager::create('44e451b3a9c4f23a7af565be43ec651db7470873fe7ff45bccf1a30156d65a4f94b15be0ed176af07a104', 5.126)
    ->isMultiThread(true);  // многопоточить как сука???????? только линукс
$bot->listen(function () use ($bot) {
    $bot->parse();
});

//callback
//echo 'Ok'; //ngrok
//$bot = ChatManager::create('44e451b3a9c4f23a7af565be43ec651db7470873fe7ff45bccf1a30156d65a4f94b15be0ed176af07a104', 5.126)
//    ->setConfirm('056431e8');
//$bot->parse();