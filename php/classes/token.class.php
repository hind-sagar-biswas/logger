<?php

class Token extends User
{
    protected $tokenTable = 'login_token';


    protected function createTokenTable()
    {
        $sql = "CREATE TABLE $this->tokenTable (
                        id               INT AUTO_INCREMENT PRIMARY KEY,
                        selector         VARCHAR(255) NOT NULL,
                        hashed_validator VARCHAR(255) NOT NULL,
                        user_id          INT      NOT NULL,
                        expiry           DATETIME NOT NULL,
                        CONSTRAINT fk_user_id
                            FOREIGN KEY (user_id)
                                REFERENCES $this->userTable (id) ON DELETE CASCADE);";

        if ($this->conn()->query($sql) == TRUE) return True;
        return False;
    }
 

    protected function generate_tokens(): array
    {
        $selector = bin2hex(random_bytes(16));
        $validator = bin2hex(random_bytes(32));
        return [$selector, $validator, $selector . ':' . $validator];
    }


    protected function parse_token(string $token): ?array
    {
        $parts = explode(':', $token);
        if ($parts && count($parts) == 2) return [$parts[0], $parts[1]];
        return null;
    }


    protected function insert_user_token(int $user_id, string $selector, string $validator, string $expiry): bool
    {
        $hashed_validator = hash('sha256', $validator);

        $conn = $this->conn();
        $sql = "INSERT INTO $this->tokenTable (user_id, selector, hashed_validator, expiry) VALUES(?, ?, ?, ?)";

        $statement = $conn->prepare($sql);
        $statement->bind_param('isss', $user_id, $selector, $hashed_validator, $expiry);
        $result = $statement->execute();
        
        $statement->close();
        $conn->close();

        return true;
    }


    protected function insert_user_token_pdo(int $user_id, string $selector, string $validator, string $expiry): bool
    {
        $hashed_validator = hash('sha256', $validator);

        $sql = "INSERT INTO $this->tokenTable (user_id, selector, hashed_validator, expiry)
            VALUES(:user_id, :selector, :hashed_validator, :expiry)";

        $statement = $this->conn_pdo('put')->prepare($sql);
        $statement->bindValue(':user_id', $user_id);
        $statement->bindValue(':selector', $selector);
        $statement->bindValue(':hashed_validator', $hashed_validator);
        $statement->bindValue(':expiry', $expiry);

        return $statement->execute();
    }


    protected function find_user_token_by_selector(string $selector)
    {
        $conn = $this->conn();

        $sql = "SELECT id, selector, hashed_validator, user_id, expiry
                FROM $this->tokenTable
                WHERE selector = ? AND expiry >= now()
                LIMIT 1";

        $statement = $conn->prepare($sql);
        $statement->bind_param('s', $selector);
        $statement->execute();
        $result = $statement->get_result()->fetch_assoc();

        
        $statement->close();
        $conn->close();

        return $result;
    }


    protected function find_user_by_token(string $token)
    {
        $conn = $this->conn();
        $tokens = $this->parse_token($token);

        if (!$tokens) return null;

        $sql = "SELECT users.id, username
            FROM $this->userTable AS users
            INNER JOIN $this->tokenTable ON user_id = users.id
            WHERE selector = ? AND
                expiry > now()
            LIMIT 1";

        $statement = $conn->prepare($sql);
        $statement->bind_param('s', $tokens[0]);
        $statement->execute();
        $result = $statement->get_result()->fetch_assoc();

        
        $statement->close();
        $conn->close();

        return $result;
    }


    protected function validate_token($token)
    {
        [$selector, $validator] = $this->parse_token($token);
        $tokens = $this->find_user_token_by_selector($selector);

        if (!$tokens) return false;
        if (hash('sha256', $validator) != $tokens['hashed_validator']) return false;
        
        return $tokens;
    }


    protected function delete_user_token(int $user_id): bool
    {
        $conn = $this->conn();
        $sql = "DELETE FROM $this->tokenTable WHERE user_id = ?";
        $statement = $conn->prepare($sql);
        $statement->bind_param('i', $user_id);
        $result = $statement->execute();
        
        $statement->close();
        $conn->close();

        return $result;
    }


    public function initialize()
    {
        $this->createUserTable();
        $this->createTokenTable();
    }
};
