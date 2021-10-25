<?php
header('Access-Control-Allow-Origin: *');
header('Content-type: Application/json');

include_once $_SERVER['DOCUMENT_ROOT']."/ugdms/def/index.php";
include_once SERVER."db/conn.php";
include_once SERVER."models/Folder.php";
include_once SERVER."models/Group.php";
include_once SERVER."libraries/lib.php";

// Initialize DB
$DBH = new DB();
$db = $DBH->connect();

// Initialize Folder
$folder = new Folder($db);

// Initialize Group
$group = new Group($db);

if(isset($_POST['id']) & isset($_POST['user_group'])){
    $folder->id = sanitize_text($_POST['id']);
    $folder->upload = sanitize_text($_POST['upload']);
    $folder->view = sanitize_text($_POST['view']);
    $folder->download = sanitize_text($_POST['download']);
    $folder->delete = sanitize_text($_POST['delete']);
    $folder->user_group = sanitize_text($_POST['user_group']);

    $group->view = sanitize_text($_POST['view']);
    $group->upload = sanitize_text($_POST['upload']);
    $group->download = sanitize_text($_POST['download']);
    $group->delete = sanitize_text($_POST['delete']);
    $group->id = sanitize_text($_POST['user_group']);

    if($folder->set_group() & $group->set_permission()){
        echo json_encode(array('status'=>true));
    }else{
        echo json_encode(array('status'=>false, 'msg'=>'Failed to set group and permissions'));
    }
}else{
    echo json_encode(array('status'=>true, 'msg'=>'Failed authentication'));
}