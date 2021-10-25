<?php

class Users{
    private $DBH;
    private $table = 'user_table';
    public $id;
    public $fullname;
    public $username;
    public $password;
    public $string;
    public $office;
    public $creator;
    public $created_at;

    public function __construct($db){
        $this->DBH = $db;
    }

    public function fetch(){
        $query = "SELECT * FROM ".$this->table;

        $STH = $this->DBH->prepare($query);

        $STH->execute();

        $data = array();

        if($STH->rowCount()){
            while($row = $STH->fetch(PDO::FETCH_OBJ)){
                $item = array(
                    'id'=>(int)$row->id,
                    'fullname'=>$row->fullname,
                    'username'=>$row->username,
                    'creator'=>$row->creator,
                    'office'=>$row->office
                );
                array_push($data, $item);
            }
            return $data;
        }else{
            return null;
        }
    }

    public function creator_users(){
        $query = "SELECT * FROM ".$this->table." WHERE `creator`=?";

        $STH = $this->DBH->prepare($query);

        $STH->execute([$this->creator]);

        $data = array();

        if($STH->rowCount()){
            while($row = $STH->fetch(PDO::FETCH_OBJ)){
                $item = array(
                    'id'=>(int)$row->id,
                    'fullname'=>$row->fullname,
                    'username'=>$row->username,
                    'creator'=>$row->creator,
                    'office'=>$row->office
                );
                array_push($data, $item);
            }
            return $data;
        }else{
            return null;
        }
    }

    public function single(){
        $query = "SELECT * FROM ".$this->table." WHERE `id`=?";

        $STH = $this->DBH->prepare($query);

        $STH->execute([$this->id]);

        if($STH->rowCount()){
            $row = $STH->fetch(PDO::FETCH_OBJ);
            $item = array(
                'id'=>(int)$row->id,
                'fullname'=>$row->fullname,
                'username'=>$row->username,
                'creator'=>$row->creator,
                'office'=>$row->office
            );
            return $item;
        }else{
            return null;
        }
    }

    public function create(){
        $query = "INSERT INTO ".$this->table." 
        (`fullname`, `username`, `password`, `creator`, `office`) 
        VALUES 
        (:fullname, :username, :password, :creator, :office)";

        $STH = $this->DBH->prepare($query);

        $this->password = password_hash($this->password, PASSWORD_DEFAULT);

        $STH->bindParam(':fullname', $this->fullname);
        $STH->bindParam(':username', $this->username);
        $STH->bindParam(':password', $this->password);
        $STH->bindParam(':creator', $this->creator);
        $STH->bindParam(':office', $this->office);

        $STH->execute();

        if($STH->rowCount()){
            return true;
        }else{
            return false;
        }
    }

    public function updateUser(){
        $query = "UPDATE ".$this->table." SET `fullname`=?, `password`=?, `office`=? WHERE `id`=?";

        $STH = $this->DBH->prepare($query);

        $this->password = password_hash($this->password, PASSWORD_DEFAULT);

        $STH->execute([$this->fullname, $this->password, $this->office, $this->id]);

        if($STH->rowCount()){
            return true;
        }else{
            return false;
        }
    }

    public function updateUserInfo(){
        $query = "UPDATE ".$this->table." SET `fullname`=?, `office`=? WHERE `id`=?";

        $STH = $this->DBH->prepare($query);

        $STH->execute([$this->fullname, $this->office, $this->id]);

        if($STH->rowCount()){
            return true;
        }else{
            return false;
        }
    }

    public function verify(){
        $query = "SELECT * FROM ".$this->table." WHERE `username`=?";

        $STH = $this->DBH->prepare($query);

        $STH->execute([$this->username]);

        if($STH->rowCount()){
            $row = $STH->fetch(PDO::FETCH_OBJ);
            $pass = $row->password;
            if(password_verify($this->password, $pass)){
                $arr = array(
                    'status'=>true,
                    'id'=>(int)$row->id,
                    'fullname'=>$row->fullname,
                    'username'=>$row->username,
                    'creator'=>$row->creator,
                    'office'=>$row->office
                );
                return $arr;
            }else{
                return array('status'=>false);
            }
        }else{
            return array('status'=>false);
        }
    }

    public function handover(){
        $query = "UPDATE ".$this->table." SET `creator`=? WHERE `creator`=?";

        $STH = $this->DBH->prepare($query);

        $STH->execute([$this->string, $this->creator]);

        if($STH->rowCount()){
            return true;
        }else{
            return false;
        }
    }

    public function deleteAction(){
        $query = "DELETE FROM ".$this->table." WHERE `id`=?";

        $STH = $this->DBH->prepare($query);

        $STH->execute([$this->id]);

        if($STH->rowCount()){
            return true;
        }else{
            return false;
        }
    }
}