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

    protected function find_user_by_username(string $nameOrMail): array {
        $sql = "SELECT id, username
                    FROM $this->userTable
                    WHERE username = ? OR 
                            email = ?
                    LIMIT 1";
                    
        $statement = $this->conn()->prepare($sql);
        $statement->bind_param('ss', $nameOrMail, $nameOrMail);
        $statement->execute();
        $result = $statement->get_result();

        return $result->fetch_assoc();
    }

    protected function find_user_by_id(int $uid): array {
        $sql = "SELECT *
                    FROM $this->userTable
                    WHERE id = ?
                    LIMIT 1";

        $statement = $this->conn()->prepare($sql);
        $statement->bind_param('i', $uid);
        $statement->execute();
        $result = $statement->get_result();

        return $result->fetch_assoc();
    }

    protected function delete_user_by_id(int $uid): bool
    {
        $sql = "DELETE FROM $this->userTable WHERE id = ?";
        $statement = $this->conn()->prepare($sql);
        $statement->bind_param('i', $uid);

        return $statement->execute();
    }
};
