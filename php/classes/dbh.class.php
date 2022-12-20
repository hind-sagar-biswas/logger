<?php

class Dbh extends Contr
{
    
    private $host = "localhost";
    private $user = "root";
    private $pass = "";
    private $dbName = "logger";

    public function setDbInfo($DB)
    {
        $this->host = $DB['host'];
        $this->user = $DB['user'];
        $this->pass = $DB['pass'];
        $this->dbName = $DB['name'];
    }

    public function setPort(int $port)
    {
        $this->port = $port;
    }

    protected function conn()
    {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        try {
            $mysqli = new mysqli($this->host, $this->user, $this->pass, $this->dbName);
            $mysqli->set_charset("utf8mb4");

            return $mysqli;
        } catch (Exception $e) {
            print "ERROR!: " . $e->getMessage() . "<BR>";
            die();
        }
    }

    protected function conn_pdo($pdo_type = 'fetch')
    {
        try {
            $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->db;
            $pdo = new PDO($dsn, $this->user, $this->pass);
            $pdo->query('SET NAMES utf8');
            $pdo->query('SET CHARACTER_SET utf8_unicode_ci');

            if ($pdo_type == 'put') {
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } else {
                $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            }

            return $pdo;
        } catch (PDOException $e) {
            print "ERROR!: " . $e->getMessage() . "<BR>";
            die();
        }
    }
}
