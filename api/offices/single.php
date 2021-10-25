<?php
header('Access-Control-Allow-Origin: *');
header('Content-type: Application/json');

include_once $_SERVER['DOCUMENT_ROOT']."/ugdms/def/index.php";
include_once SERVER."db/conn.php";
include_once SERVER."models/Offices.php";
include_once SERVER."libraries/lib.php";

// Get id
$id = isset($_GET['id']) & !empty($_GET['id']) ? sanitize_text($_GET['id']) : die();

// Initialize DB
$DBH = new DB();
$db = $DBH->connect();

// Initialize Offices
$offices = new Offices($db);
$offices->id = $id;

$single = $offices->single();

if(!empty($single)){
    if(!empty($single['users'])){
        $users = $single['users']; $users = rtrim($users, ',');
        $users = explode(',', $users);
        $single['user_arr'] = $users;
    }else{
        $single['user_arr'] = [];
    }
    echo json_encode($single);
}else{
    echo json_encode($single);
}