<?php

class Admin{
    private $DBH;
    private $table = 'admin_table';
    public $id;
    public $fullname;
    public $username;
    public $password;
    public $super_status;
    public $view;
    public $download;
    public $upload;
    public $delete;
    public $created_at;

    public function __construct($db){
        $this->DBH = $db;
    }

    public function fetch(){
        $query = "SELECT * FROM ".$this->table." WHERE `super_status`=1";

        $STH = $this->DBH->prepare($query);

        $STH->execute();

        if($STH->rowCount()){
            return true;
        }else{
            return false;
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
                'super_status'=>(int)$row->super_status,
                'view'=>(int)$row->view,
                'download'=>(int)$row->download,
                'upload'=>(int)$row->upload,
                'delete'=>(int)$row->delete_doc
            );
            return $item;
        }else{
            return null;
        }
    }

    public function administrators(){
        $query = "SELECT * FROM ".$this->table." WHERE `super_status`=0";

        $STH = $this->DBH->prepare($query);

        $STH->execute();

        $data = array();

        if($STH->rowCount()){
            while($row = $STH->fetch(PDO::FETCH_OBJ)){
                $item = array(
                    'id' => $row->id,
                    'fullname'=> $row->fullname,
                    'username'=>$row->username,
                    'super_status'=>(int)$row->super_status,
                    'view'=>(int)$row->view,
                    'download'=>(int)$row->download,
                    'upload'=>(int)$row->upload,
                    'delete'=>(int)$row->delete_doc,
                    'created_at'=>$row->created_at
                );
                array_push($data, $item);
            }
            return $data;
        }else{
            return null;
        }
    }

    public function createSuper(){
        $query = "INSERT INTO ".$this->table." (`fullname`, `username`, `password`) VALUES (:fullname, :username, :password)";

        $STH = $this->DBH->prepare($query);

        $this->password = password_hash($this->password, PASSWORD_DEFAULT);

        $STH->bindParam(':fullname', $this->fullname);
        $STH->bindParam(':username', $this->username);
        $STH->bindParam(':password', $this->password);

        $STH->execute();

        if($STH->rowCount()){
            return true;
        }else{
            return false;
        }
    }

    public function create(){
        $query = "INSERT INTO ".$this->table." (`fullname`, `username`, `password`, `super_status`) VALUES (:fullname, :username, :password, :super_status)";

        $STH = $this->DBH->prepare($query);

        $this->password = password_hash($this->password, PASSWORD_DEFAULT);

        $STH->bindParam(':fullname', $this->fullname);
        $STH->bindParam(':username', $this->username);
        $STH->bindParam(':password', $this->password);
        $STH->bindParam(':super_status', $this->super_status);

        $STH->execute();

        if($STH->rowCount()){
            return true;
        }else{
            return false;
        }
    }

    public function updateView(){
        $query = "UPDATE ".$this->table." SET `view`=? WHERE `id`=?";

        $STH = $this->DBH->prepare($query);

        $STH->execute([$this->view, $this->id]);

        if($STH->rowCount()){
            return true;
        }else{
            return false;
        }
    }

    public function updateDownload(){
        $query = "UPDATE ".$this->table." SET `download`=? WHERE `id`=?";

        $STH = $this->DBH->prepare($query);

        $STH->execute([$this->download, $this->id]);

        if($STH->rowCount()){
            return true;
        }else{
            return false;
        }
    }

    public function updateUpload(){
        $query = "UPDATE ".$this->table." SET `upload`=? WHERE `id`=?";

        $STH = $this->DBH->prepare($query);

        $STH->execute([$this->upload, $this->id]);

        if($STH->rowCount()){
            return true;
        }else{
            return false;
        }
    }

    public function updateDeleteDoc(){
        $query = "UPDATE ".$this->table." SET `delete_doc`=? WHERE `id`=?";

        $STH = $this->DBH->prepare($query);

        $STH->execute([$this->delete, $this->id]);

        if($STH->rowCount()){
            return true;
        }else{
            return false;
        }
    }

    public function updateAdminInfo(){
        $query = "UPDATE ".$this->table." SET `fullname`=? WHERE `id`=?";

        $STH = $this->DBH->prepare($query);

        $STH->execute([$this->fullname, $this->id]);

        if($STH->rowCount()){
            return true;
        }else{
            return false;
        }
    }

    public function updateAdmin(){
        $query = "UPDATE ".$this->table." SET `fullname`=?, `password`=? WHERE `id`=?";

        $STH = $this->DBH->prepare($query);

        $this->password = password_hash($this->password, PASSWORD_DEFAULT);

        $STH->execute([$this->fullname, $this->password, $this->id]);

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
                $arr =  array(
                    'status'=>true, 
                    'id'=>(int)$row->id,
                    'fullname'=>$row->fullname, 
                    'username'=>$row->username,
                    'super_status'=>(int)$row->super_status,
                    'view'=>(int)$row->view,
                    'download'=>(int)$row->download,
                    'upload'=>(int)$row->upload,
                    'delete'=>(int)$row->delete_doc
                );
                return $arr;
            }else{
                return array('status'=>false);
            }
        }else{
            return array('status'=>false);
        }
    }

    public function deleteAction(){
        $query = "DELETE FROM ".$this->table." WHERE `id`=? AND `super_status`=0";

        $STH = $this->DBH->prepare($query);

        $STH->execute([$this->id]);

        if($STH->rowCount()){
            return true;
        }else{
            return false;
        }
    }
}