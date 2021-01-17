<?php
error_reporting(E_ALL);
ini_set('display_startup_errors', 1);
ini_set('display_errors', '1');

use ChatManager\ChatManager;

require_once('./vendor/digitalstars/simplevk/autoload.php');
require_once('./vendor/autoload.php');

/**
 * Настрой файл config.json
 */
ChatManager::run('longpool');