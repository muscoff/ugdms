<?php

class Document{
    private $table = 'doc_table';
    private $DBH;
    public $id;
    public $name;
    public $centre;
    public $exams_date;
    public $semester;
    public $link;
    public $file_path;
    public $creator;
    public $office;
    public $parent;
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
                    'centre'=>$row->exams_centre,
                    'exams_date'=>$row->exams_date,
                    'semester'=>$row->semester,
                    'link'=>$row->link,
                    'creator'=>$row->creator,
                    'parent'=>$row->c_parent,
                    'office'=>$row->office,
                    'path'=>$row->file_path,
                );
                array_push($data, $item);
            }
            return $data;
        }else{
            return null;
        }
    }

    public function creator_document(){
        $query = "SELECT * FROM ".$this->table." WHERE `c_parent`=?";

        $STH = $this->DBH->prepare($query);

        $STH->execute([$this->parent]);

        $data = array();

        if($STH->rowCount()){
            while($row = $STH->fetch(PDO::FETCH_OBJ)){
                $item = array(
                    'id'=>(int)$row->id,
                    'name'=>$row->name,
                    'centre'=>$row->exams_centre,
                    'exams_date'=>$row->exams_date,
                    'semester'=>$row->semester,
                    'link'=>$row->link,
                    'creator'=>$row->creator,
                    'parent'=>$row->c_parent,
                    'office'=>$row->office,
                    'path'=>$row->file_path,
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
        (`name`, `exams_centre`, `exams_date`, `semester`, `creator`, `c_parent`, `office`, `file_path`, `link`) 
        VALUES 
        (:name, :centre, :exams_date, :semester, :creator, :parent, :office, :file_path, :link)";

        $STH = $this->DBH->prepare($query);

        $STH->bindParam(':name', $this->name);
        $STH->bindParam(':centre', $this->centre);
        $STH->bindParam(':exams_date', $this->exams_date);
        $STH->bindParam(':semester', $this->semester);
        $STH->bindParam(':creator', $this->creator);
        $STH->bindParam(':parent', $this->parent);
        $STH->bindParam(':office', $this->office);
        $STH->bindParam(':file_path', $this->file_path);
        $STH->bindParam(':link', $this->link);

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
                'name'=>$row->name,
                'centre'=>$row->exams_centre,
                'exams_date'=>$row->exams_date,
                'semester'=>$row->semester,
                'link'=>$row->link,
                'creator'=>$row->creator,
                'parent'=>$row->c_parent,
                'office'=>$row->office,
                'path'=>$row->file_path,
            );
            return $item;
        }else{
            return null;
        }
    }

    public function handover(){
        $query = "UPDATE ".$this->table." SET `c_parent`=? WHERE `c_parent`=?";

        $STH = $this->DBH->prepare($query);

        $STH->execute([$this->string, $this->parent]);

        if($STH->rowCount()){
            return true;
        }else{
            return false;
        }
    }

    public function delete(){
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