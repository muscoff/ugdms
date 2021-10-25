<?php
header('Access-Control-Allow-Origin: *');
include_once $_SERVER['DOCUMENT_ROOT']."/ugdms/def/index.php";
include_once SERVER."db/conn.php";
include_once SERVER."models/Users.php";
include_once SERVER."libraries/lib.php";

// initialize DB
$DBH = new DB();
$db = $DBH->connect();

// Initialize Users
$users = new Users($db);

if(isset($_POST['id']) & !empty($_POST['id'])){
    if(isset($_POST['fullname']) & isset($_POST['password']) & !empty($_POST['password'])){
        $users->fullname = sanitize_text($_POST['fullname']);
        $users->password = sanitize_text($_POST['password']);
        $users->office = sanitize_text($_POST['office']);
        $users->id = sanitize_text($_POST['id']);

        if($users->updateUser()){
            echo json_encode(array('status'=>true, 'msg'=>'Record updated'));
        }else{
            echo json_encode(array('status'=>false, 'Failed to update record'));
        }
    }else{
        $users->id = sanitize_text($_POST['id']);
        $users->fullname = sanitize_text($_POST['fullname']);
        $users->office = sanitize_text($_POST['office']);

        if($users->updateUserInfo()){
            echo json_encode(array('status'=>true, 'msg'=>'Record updated'));
        }else{
            echo json_encode(array('status'=>false, 'msg'=>'Failed to update record'));
        }
    }
}else{
    die();
}