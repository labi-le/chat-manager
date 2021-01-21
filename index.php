<?php

declare(strict_types=1);

use ChatManager\ChatManager;

require_once('./vendor/digitalstars/simplevk/autoload.php');
require_once('./vendor/autoload.php');

/**
 * Настрой файл config.json
 */
ChatManager::run('longpool');
//
//$array =  [
//    0 => [21 => 313],
//    1 => 2,
//    2 => 232,
//    3 => 3232
//];
//
//
//$result1 = \ChatManager\Models\Utils::isSeq($array);
//$result2 = \ChatManager\Models\Utils::isAssoc($array);
//$result3 = \ChatManager\Models\Utils::isMulti($array);
//
//var_dump($array, $result1, $result2, $result3);
