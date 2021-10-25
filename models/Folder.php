<?php

class Folder{
    private $table = 'folder_table';
    private $DBH;
    public $id;
    public $name;
    public $path;
    public $creator;
    public $parent;
    public $office;
    public $view;
    public $upload;
    public $download;
    public $delete;
    public $user_group;
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
                    'id'=>(int)$row->id,
                    'name'=>$row->name,
                    'path'=>$row->path,
                    'creator'=>$row->creator,
                    'parent'=>(int)$row->parent,
                    'office'=>$row->office,
                    'view'=>(int)$row->view,
                    'upload'=>(int)$row->upload,
                    'download'=>(int)$row->download,
                    'delete'=>(int)$row->del,
                    'user_group'=>$row->user_group,
                    'created_at'=>$row->created_at
                );
                array_push($data, $item);
            }
            return $data;
        }else{
            return null;
        }
    }

    public function creator_folder(){
        $query = "SELECT * FROM ".$this->table." WHERE `creator`=?";

        $STH = $this->DBH->prepare($query);

        $STH->execute([$this->creator]);

        $data = array();

        if($STH->rowCount()){
            while($row = $STH->fetch(PDO::FETCH_OBJ)){
                $item = array(
                    'id'=>(int)$row->id,
                    'name'=>$row->name,
                    'path'=>$row->path,
                    'creator'=>$row->creator,
                    'parent'=>(int)$row->parent,
                    'office'=>$row->office,
                    'view'=>(int)$row->view,
                    'upload'=>(int)$row->upload,
                    'download'=>(int)$row->download,
                    'delete'=>(int)$row->del,
                    'user_group'=>$row->user_group,
                    'created_at'=>$row->created_at
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
        (`name`, `path`, `creator`, `parent`, `office`, `view`, `upload`, `download`, `del`) 
        VALUES 
        (:name, :path, :creator, :parent, :office, :view, :upload, :download, :delete)";

        $STH = $this->DBH->prepare($query);

        $STH->bindParam(':name', $this->name);
        $STH->bindParam(':path', $this->path);
        $STH->bindParam(':creator', $this->creator);
        $STH->bindParam(':parent', $this->parent);
        $STH->bindParam(':office', $this->office);
        $STH->bindParam(':view', $this->view);
        $STH->bindParam(':upload', $this->upload);
        $STH->bindParam(':download', $this->download);
        $STH->bindParam(':delete', $this->delete);

        $STH->execute();

        if($STH->rowCount()){
            return true;
        }else{
            return false;
        }
    }

    public function set_group(){
        $query = "UPDATE ".$this->table." SET `view`=?, `upload`=?, `download`=?, `del`=?, `user_group`=? WHERE `id`=?";

        $STH = $this->DBH->prepare($query);

        $STH->execute([$this->view, $this->upload, $this->download, $this->delete, $this->user_group, $this->id]);

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