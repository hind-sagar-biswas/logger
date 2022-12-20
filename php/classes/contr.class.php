<?php 

class Contr {
    protected $DEBUG = False;

    protected function setCookie(string $name, $value, int $expiry = 30): void
    {
        setcookie($name, $value, $expiry, '/');
    }
}