<?php
header('Access-Control-Allow-Origin: *');
header('Content-type: Application/json');

include_once $_SERVER['DOCUMENT_ROOT']."/ugdms/def/index.php";
include_once SERVER."db/conn.php";
include_once SERVER."models/Group.php";
include_once SERVER."libraries/lib.php";

$creator = isset($_GET['creator']) & !empty($_GET['creator']) ? sanitize_text($_GET['creator']) : die();

// Initialize DB
$DBH = new DB();
$db = $DBH->connect();

// Initialize Group
$group = new Group($db);
$group->creator = $creator;

$creator_group = $group->creator_group();

$data = array();

if(!empty($creator_group)){
    foreach($creator_group as $value){
        $users_arr = [];
        if(!empty($value['users'])){
            $users = $value['users'];
            $users = rtrim($users, ',');
            $users = explode(',', $users);
            $users_arr = $users;
        }
        $value['users_arr'] = $users_arr;
        array_push($data, $value);
    }
    echo json_encode($data);
}else{
    echo json_encode($data);
}