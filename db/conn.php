<?php

class DB{
    private $host = 'localhost';
    private $root = 'root';
    private $pass = '';
    private $db = 'ugdms';
    private $DBH;

    public function connect(){
        $this->DBH = null;

        try{
            $this->DBH = new PDO("mysql:host=".$this->host.";dbname=".$this->db, $this->root, $this->pass);
        }
        catch(PDOException $e){
            echo $e->getMessage();
        }
        return $this->DBH;
    }
}