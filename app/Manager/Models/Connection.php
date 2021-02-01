<?php

namespace Manager\Models;

use Manager\Launcher;
use PDO;

class Connection
{
    public static function make(): PDO
    {
        return new PDO(
            Launcher::database()->dsn,
            Launcher::database()->login,
            Launcher::database()->password);
    }

    public static function manualMake(string $dsn, string $login, string $password): PDO
    {
        return new PDO($dsn, $login, $password);
    }
}