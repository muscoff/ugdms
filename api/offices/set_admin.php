<?php
header('Access-Control-Allow-Origin: *');
header('Content-type: Application/json');

include_once $_SERVER['DOCUMENT_ROOT']."/ugdms/def/index.php";
include_once SERVER."db/conn.php";
include_once SERVER."models/Offices.php";
include_once SERVER."libraries/lib.php";

// Initialize DB
$DBH = new DB();
$db = $DBH->connect();

// Initialize Offices
$offices = new Offices($db);

if(isset($_POST['id']) & isset($_POST['admin']) & !empty($_POST['id']) & !empty($_POST['admin'])){
    $offices->id = sanitize_text($_POST['id']);
    $offices->parent = sanitize_text($_POST['id']);
    $offices->admin = sanitize_text($_POST['admin']);

    if($offices->set_admin()){
        echo json_encode(array('status'=>true));
    }else{
        echo json_encode(array('status'=>false, 'msg'=>'Failed to update record'));
    }
}else{
    echo json_encode(array('status'=>false, 'msg'=>'An administrator is expected'));
}