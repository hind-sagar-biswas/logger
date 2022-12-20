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
                    `password` VARCHAR(225)                             NOT NULL , 
                    `create_time` DATETIME                              NOT NULL DEFAULT CURRENT_TIMESTAMP , 
                    `update_time` DATETIME  on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , 
                        PRIMARY KEY (`id`), 
                        UNIQUE `username` (`username`), 
                        UNIQUE `EMAIL` (`email`)) 
                            ENGINE = InnoDB;";

        if ($this->conn()->query($sql) == TRUE) return True;
        return False;
    }

    protected function find_user_by_username(string $nameOrMail)
    {
        echo "<br>$nameOrMail<br>";

        $conn = $this->conn();
        $sql = "SELECT id, username, password
                    FROM $this->userTable
                    WHERE username = ? OR 
                            email = ?
                    LIMIT 1";

        $statement = $conn->prepare($sql);
        $statement->bind_param('ss', $username, $email);

        $username = $nameOrMail;
        $email = $nameOrMail;
        $statement->execute();
        $result = $statement->get_result()->fetch_assoc();
        
        $statement->close();
        $conn->close();
        
        return $result;
    }

    protected function find_user_by_id(int $uid): array
    {
        $conn = $this->conn();
        $sql = "SELECT *
                    FROM $this->userTable
                    WHERE id = ?
                    LIMIT 1";

        $statement = $conn->prepare($sql);
        $statement->bind_param('i', $uid);
        $statement->execute();
        $result = $statement->get_result()->fetch_assoc();

        $statement->close();
        $conn->close();

        return $result;
    }

    protected function add_new_user($username, $email, $hashed_password)
    {
        $conn = $this->conn();
        $sql = "INSERT INTO $this->userTable (username, email, password) VALUES('$username', '$email', '$hashed_password')";
        $getLastEntrySql = "SELECT * FROM $this->userTable ORDER BY id DESC LIMIT 1";
        if($this->conn()->query($sql)) return mysqli_fetch_assoc(mysqli_query($conn, $getLastEntrySql));
        return False;
    }

    protected function delete_user_by_id(int $uid): bool
    {
        $conn = $this->conn();

        $sql = "DELETE FROM $this->userTable WHERE id = ?";
        $statement = $conn->prepare($sql);
        $statement->bind_param('i', $uid);

        $statement->close();
        $conn->close();

        return $statement->execute();
    }

    protected function check_user_exists(string $value, string $col): bool
    {
        $conn = $this->conn();

        $sql = "SELECT id FROM $this->userTable WHERE $col = '$value'";
        $result = $conn->query($sql);

        $conn->close();
        
        if (!$result) return True;
        if (mysqli_num_rows($result) > 0) return True;
        return False;
    }
};
