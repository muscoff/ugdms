<?php

class Offices{
    private $table = 'office_table';
    private $DBH;
    public $id;
    public $name;
    public $location;
    public $parent;
    public $parent_path;
    public $admin;
    public $users;
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
                    'location'=>$row->location,
                    'parent'=>(int)$row->parent,
                    'parent_path'=>$row->parent_path,
                    'admin'=>$row->admin,
                    'users'=>$row->users,
                    'created_at'=>$row->created_at
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
                'id'=>$row->id,
                'name'=>$row->name,
                'location'=>$row->location,
                'parent'=>(int)$row->parent,
                'parent_path'=>$row->parent_path,
                'admin'=>$row->admin,
                'users'=>$row->users,
                'created_at'=>$row->created_at
            );
            return $item;
        }else{
            return null;
        }
    }

    public function create(){
        $query = "INSERT INTO ".$this->table." 
        (`name`, `location`, `parent`, `parent_path`) 
        VALUES 
        (:name, :location, :parent, :parent_path)";

        $STH = $this->DBH->prepare($query);

        $STH->bindParam(':name', $this->name);
        $STH->bindParam(':location', $this->location);
        $STH->bindParam(':parent', $this->parent);
        $STH->bindParam(':parent_path', $this->parent_path);

        $STH->execute();

        if($STH->rowCount()){
            return true;
        }else{
            return false;
        }
    }

    public function create_with_admin(){
        $query = "INSERT INTO ".$this->table." 
        (`name`, `location`, `parent`, `parent_path`, `admin`) 
        VALUES 
        (:name, :location, :parent, :parent_path, :admin)";

        $STH = $this->DBH->prepare($query);

        $STH->bindParam(':name', $this->name);
        $STH->bindParam(':location', $this->location);
        $STH->bindParam(':parent', $this->parent);
        $STH->bindParam(':parent_path', $this->parent_path);
        $STH->bindParam(':admin', $this->admin);

        $STH->execute();

        if($STH->rowCount()){
            return true;
        }else{
            return false;
        }
    }

    public function set_admin(){
        $query = "UPDATE ".$this->table." SET `admin`=? WHERE `id`=? OR `parent`=?";

        $STH = $this->DBH->prepare($query);

        $STH->execute([$this->admin, $this->id, $this->parent]);

        if($STH->rowCount()){
            return true;
        }else{
            return false;
        }
    }

    public function handover(){
        $query = "UPDATE ".$this->table." SET `admin`=? WHERE `id`=? OR `parent`=?";

        $STH = $this->DBH->prepare($query);

        $STH->execute([$this->admin, $this->id, $this->parent]);

        if($STH->rowCount()){
            return true;
        }else{
            return false;
        }
    }
}