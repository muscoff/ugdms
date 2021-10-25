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

if(isset($_POST['id']) & isset($_POST['users']) & !empty($_POST['id']) & !empty($_POST['users'])){
    $group->id = (int)sanitize_text($_POST['id']);
    $group->users = sanitize_text($_POST['users']);
    $group->users = rtrim($group->users, ',');
    $group->view = (int)sanitize_text($_POST['view']);
    $group->upload = (int)sanitize_text($_POST['upload']);
    $group->download = (int)sanitize_text($_POST['download']);
    $group->delete = (int)sanitize_text($_POST['delete']);

    $arr = array(
        'id'=>$group->id, 'users'=>$group->users, 'view'=>$group->view, 'upload'=>$group->upload,
        'download'=>$group->download, 'delete'=>$group->delete
    );

    if($group->set_users()){
        echo json_encode(array('status'=>true));
    }else{
        echo json_encode(array('status'=>false, 'msg'=>''));
    }
}else{
    echo json_encode(array('status'=>false, 'msg'=>'Group Id and selected users is required to proceed'));
}