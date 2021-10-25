<?php

class Group{
    private $table = 'group_table';
    private $DBH;
    public $id;
    public $name;
    public $creator;
    public $users;
    public $upload;
    public $download;
    public $view;
    public $delete;
    public $string;
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
                    'id'=>$row->id,
                    'name'=>$row->name,
                    'creator'=>$row->creator,
                    'users'=>$row->users,
                    'upload'=>(int)$row->upload,
                    'download'=>(int)$row->download,
                    'view'=>(int)$row->view,
                    'delete'=>(int)$row->del,
                    'created_at'=>$row->created_at
                );
                array_push($data, $item);
            }
            return $data;
        }else{
            return null;
        }
    }

    public function creator_group(){
        $query = "SELECT * FROM ".$this->table." WHERE `creator`=?";

        $STH = $this->DBH->prepare($query);
        
        $STH->execute([$this->creator]);

        $data = array();

        if($STH->rowCount()){
            while($row = $STH->fetch(PDO::FETCH_OBJ)){
                $item = array(
                    'id'=>$row->id,
                    'name'=>$row->name,
                    'creator'=>$row->creator,
                    'users'=>$row->users,
                    'upload'=>(int)$row->upload,
                    'download'=>(int)$row->download,
                    'view'=>(int)$row->view,
                    'delete'=>(int)$row->del
                );
                array_push($data, $item);
            }
            return $data;
        }else{
            return null;
        }
    }

    public function create(){
        $query = "INSERT INTO ".$this->table." 
        (`name`, `creator`, `upload`, `download`, `view`, `del`) 
        VALUES 
        (:name, :creator, :upload, :download, :view, :delete)";

        $STH = $this->DBH->prepare($query);

        $STH->bindParam(':name', $this->name);
        $STH->bindParam(':creator', $this->creator);
        $STH->bindParam(':upload', $this->upload);
        $STH->bindParam(':download', $this->download);
        $STH->bindParam(':view', $this->view);
        $STH->bindParam(':delete', $this->delete);

        $STH->execute();

        if($STH->rowCount()){
            return true;
        }else{
            return false;
        }
    }

    public function set_users(){
        $query = "UPDATE ".$this->table." SET `users`=?, `upload`=?, `download`=?, `view`=?, `del`=? WHERE `id`=?";

        $STH = $this->DBH->prepare($query);

        $STH->execute([$this->users, $this->upload, $this->download, $this->view, $this->delete, $this->id]);

        if($STH->rowCount()){
            return true;
        }else{
            return false;
        }
    }

    public function set_permission(){
        $query = "UPDATE ".$this->table." SET `upload`=?, `download`=?, `view`=?, `del`=? WHERE `id`=?";

        $STH = $this->DBH->prepare($query);

        $STH->execute([$this->upload, $this->download, $this->view, $this->delete, $this->id]);

        if($STH->rowCount()){
            return true;
        }else{
            return false;
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
}