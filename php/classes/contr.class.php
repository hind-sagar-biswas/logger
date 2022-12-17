<?php

class Contr
{
    protected $BASE_URL = 'http://localhost:8000';

    protected $URLS = [
        "root" => "/",
    ];

    protected function redirect_to($target, $type = "name")
    {
        if($type == "name") header("Location: " . $this->BASE_URL . $this->URLS[$target]);
        else header("Location: " . $this->URLS[$target]);
    }
}
