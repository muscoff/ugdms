<?php
header('Access-Control-Allow-Origin: *');
header('Content-type: Application/json');

include_once $_SERVER['DOCUMENT_ROOT']."/ugdms/def/index.php";
include_once SERVER."db/conn.php";
include_once SERVER."models/Folder.php";
include_once SERVER."libraries/lib.php";

// Initialize DB
$DBH = new DB();
$db = $DBH->connect();

// Initialize Folder
$folder = new Folder($db);

if(isset($_POST['name']) & !empty($_POST['name'])){
    $folder->name = sanitize_text($_POST['name']);
    $folder->creator = sanitize_text($_POST['creator']);
    $folder->office = sanitize_text($_POST['office']);
    $folder->view = (int)sanitize_text($_POST['view']);
    $folder->upload = (int)sanitize_text($_POST['upload']);
    $folder->download = (int)sanitize_text($_POST['download']);
    $folder->delete = (int)sanitize_text($_POST['delete']);
    $folder->path = sanitize_text($_POST['path']);
    $folder->parent = sanitize_text($_POST['parent']);
    $path = SERVER.$folder->path;

    $arr = array(
        'name'=>$folder->name,
        'creator'=>$folder->creator,
        'path'=>$folder->path,
        'parent'=>$folder->parent,
        'office'=>$folder->office,
        'view'=>$folder->view,
        'upload'=>$folder->upload,
        'download'=>$folder->download,
        'delete'=>$folder->delete
    );

    //echo json_encode($arr);

    if(!file_exists($path)){
        if($folder->create()){
            mkdir($path);
            echo json_encode(array('status'=>true, 'msg'=>'Folder created'));
        }else{
            echo json_encode(array('status'=>false, 'msg'=>'Failed to create folder'));
        }
    }else{
        echo json_encode(array('status'=>false, 'msg'=>'Folder name already exist'));
    }
}else{
    echo json_encode(array('status'=>false, 'msg'=>'Folder name cannot be empty'));
}