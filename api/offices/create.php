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

if(isset($_POST['name']) & !empty($_POST['name'])){
    $offices->name = sanitize_text($_POST['name']);
    $offices->parent = sanitize_text($_POST['parent']);
    $offices->parent = (int)$offices->parent;
    $offices->location = sanitize_text($_POST['location']);
    $offices->parent_path = sanitize_text($_POST['parent_path']);

    if($offices->parent == 0){
        if($offices->create()){
            echo json_encode(array('status'=>true));
        }else{
            echo json_encode(array('status'=>false, 'msg'=>'Failed to create office'));
        }
    }else{
        $single = json_decode(file_get_contents(url."api/offices/single.php?id=".$offices->parent),true);
        $admin = $single['admin'];
        $offices->admin = $admin;

        if($offices->create_with_admin()){
            echo json_encode(array('status'=>true));
        }else{
            echo json_encode(array('status'=>false, 'msg'=>'Failed to create office unit'));
        }
    }
}else{
    echo json_encode(array('status'=>false, 'msg'=>'Office name is required'));
}