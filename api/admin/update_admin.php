<?php
header('Access-Control-Allow-Origin: *');
include_once $_SERVER['DOCUMENT_ROOT']."/ugdms/def/index.php";
include_once SERVER."db/conn.php";
include_once SERVER."models/Admin.php";
include_once SERVER."libraries/lib.php";

// initialize DB
$DBH = new DB();
$db = $DBH->connect();

// Initialize Admin
$admin = new Admin($db);

if(isset($_POST['id']) & !empty($_POST['id'])){
    if(isset($_POST['fullname']) & isset($_POST['password']) & !empty($_POST['password'])){
        $admin->fullname = sanitize_text($_POST['fullname']);
        $admin->password = sanitize_text($_POST['password']);
        $admin->id = sanitize_text($_POST['id']);

        if($admin->updateAdmin()){
            echo json_encode(array('status'=>true));
        }else{
            echo json_encode(array('status'=>false));
        }
    }else{
        $admin->id = sanitize_text($_POST['id']);
        $admin->fullname = sanitize_text($_POST['fullname']);

        if($admin->updateAdminInfo()){
            echo json_encode(array('status'=>true));
        }else{
            echo json_encode(array('status'=>false));
        }
    }
}else{
    die();
}