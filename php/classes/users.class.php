<?php

class User extends Dbh
{
    protected $userTable = 'users';

    protected function createUserTable()
    {
        $sql = "CREATE TABLE $this->userTable (
                    `id` INT(11) NOT NULL AUTO_INCREMENT , 
                    `username` VARCHAR(225)                             NOT NULL , 
                    `email`    VARCHAR(225)                             NOT NULL , 
                    `create_time` DATETIME                              NOT NULL DEFAULT CURRENT_TIMESTAMP , 
                    `update_time` DATETIME  on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , 
                        PRIMARY KEY (`id`), 
                        UNIQUE `username` (`username`), 
                        UNIQUE `EMAIL` (`email`)) 
                            ENGINE = InnoDB;";

        if ($this->conn()->query($sql) == TRUE) return True;
        return False;
    }
};
