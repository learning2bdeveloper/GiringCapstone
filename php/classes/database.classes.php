<?php

class Database
{

    private $servername;
    private $dbname;

    public function connect()
    {

        $this->servername = 'localhost';
        $this->dbname = 'capstone';

        try {

            $pdo = new PDO("mysql:host=" . $this->servername . ";dbname=" . $this->dbname, "root", "");
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            return $pdo;

        } catch (PDOException $e) {
            echo "Connection Error To The Database!" . $e->getMessage();
            die();
        }
    }

}
