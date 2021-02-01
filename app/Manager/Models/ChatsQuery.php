<?php


namespace Manager\Models;

use PDOStatement;

trait ChatsQuery
{
    private string $table_name = 'chats';

    /**
     * Создать таблицу
     * @return false|PDOStatement
     */
    public function createChatTable()
    {
        $sql = "CREATE TABLE `$this->table_name`(
                `id` INT(11) NOT NULL UNIQUE,
                `welcome_text` TEXT NOT NULL,
                `welcome_text-status` TINYINT(1) NOT NULL,
                `auto_kick-status` TINYINT(1) NOT NULL)";

        return $this->db->query($sql);
    }


    /**
     * Создать новую запись в таблице
     * @param $id
     * @return bool
     */
    public function createChatRecord($id): bool
    {
        return $this->db->createRecord($id,
            [
                'id' => $id,
                'welcome_text' => 'Hi ~!fn~',
                'welcome_text-status' => 0,
                'auto_kick-status' => 0
                //etc..
            ]);
    }
}