<?php
header('Access-Control-Allow-Origin: *');
header('Content-type: Application/json');

include_once $_SERVER['DOCUMENT_ROOT']."/ugdms/def/index.php";
include_once SERVER."db/conn.php";
include_once SERVER."models/Offices.php";

// Initialize DB
$DBH = new DB();
$db = $DBH->connect();

// Initialize Offices
$offices = new Offices($db);

$fetch = $offices->fetch();

$data = array();

if(!empty($fetch)){
    foreach($fetch as $value){
        if(!empty($value['users'])){
            $users = $value['users']; $users = rtrim($users, ',');
            $users = explode(',', $users);
            $value['user_arr'] = $users;
            array_push($data, $value);
        }else{
            $value['user_arr'] = [];
            array_push($data, $value);
        }
    }
    echo json_encode($data);
}else{
    echo json_encode($data);
}