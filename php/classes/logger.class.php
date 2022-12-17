<?php

class Logger extends Token
{
    public function login($nameOrMail, string $password, bool $rememberMe = false): bool
    {
        $user = $this->find_user_by_username($nameOrMail);

        // if user found, check the password
        if ($user && md5($password) == $user['password']) {
            $this->session_login($user);
            if ($rememberMe) $this->remember_me($user['id']);
            return true;
        }

        return false;
    }

    /**
     * log a user in the session
     * @param array $user
     * @return bool
     */
    public function session_login(array $user): bool
    {
        // prevent session fixation attack
        if (session_regenerate_id()) {
            // set username & id in the session
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_id'] = $user['id'];
            return true;
        }

        return false;
    }


    /**
     * checks if the user is already logged in
     * @param null
     * @return bool
     */
    public function checkLogin()
    {
        // check the session
        if (isset($_SESSION['username'])) {
            return true;
        }

        // check the remember_me in cookie
        $token = filter_input(INPUT_COOKIE, 'remember_me');

        if ($token && $this->validate_token($token)) {

            $user = $this->find_user_by_token($token);

            if ($user) {
                return $this->session_login($user);
            }
        }
        return false;
    }


    public function remember_me(int $user_id, int $day = 30)
    {
        [$selector, $validator, $token] = $this->generate_tokens();

        // remove all existing token associated with the user id
        $this->delete_user_token($user_id);

        // set expiration date
        $expired_seconds = time() + 60 * 60 * 24 * $day;

        // insert a token to the database
        $hash_validator = md5($validator);
        $expiry = date('Y-m-d H:i:s', $expired_seconds);

        if ($this->insert_user_token($user_id, $selector, $hash_validator, $expiry)) setcookie('remember_me', $token, $expired_seconds);
    }


    public function logout(): bool
    {
        if ($this->checkLogin()) {
            // delete the user token
            $this->delete_user_token($_SESSION['user_id']);

            // delete session
            unset($_SESSION['username'], $_SESSION['user_id`']);

            // remove the remember_me cookie
            if (isset($_COOKIE['remember_me'])) {
                unset($_COOKIE['remember_me']);
                setcookie('remember_user', null, -1);
            }

            // remove all session data
            session_destroy();

            return True;
        }
    }
}
