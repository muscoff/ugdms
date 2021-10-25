<?php
header('Access-Control-Allow-Origin: *');
header('Content-type: Application/json');

include_once $_SERVER['DOCUMENT_ROOT']."/ugdms/def/index.php";
include_once SERVER."db/conn.php";
include_once SERVER."models/Group.php";
include_once SERVER."libraries/lib.php";

// Initialize DB
$DBH = new DB();
$db = $DBH->connect();

// Initialize Group
$group = new Group($db);

if(isset($_POST['name']) & !empty($_POST['name'])){
    $group->name = sanitize_text($_POST['name']);
    $group->creator = sanitize_text($_POST['creator']);
    $group->view = (int)sanitize_text($_POST['view']);
    $group->upload = (int)sanitize_text($_POST['upload']);
    $group->delete = (int)sanitize_text($_POST['delete']);
    $group->download = (int)sanitize_text($_POST['download']);

    if($group->create()){
        echo json_encode(array('status'=>true));
    }else{
        echo json_encode(array('status'=>false, 'msg'=>'Failed to create group'));
    }
}else{
    echo json_encode(array('status'=>false, 'msg'=>'Group name is required'));
}