<?php
header('Access-Control-Allow-Origin: *');
header('Content-type: Application/json');

include_once $_SERVER['DOCUMENT_ROOT']."/ugdms/def/index.php";
include_once SERVER."db/conn.php";
include_once SERVER."models/Users.php";
include_once SERVER."libraries/lib.php";

// Get creator
$creator = isset($_GET['creator']) & !empty($_GET['creator']) ? sanitize_text(($_GET['creator'])) : die();

// Initialize DB
$DBH = new DB();
$db = $DBH->connect();

// Initialize Users
$users = new Users($db);

$users->creator = $creator;

$creator_users = $users->creator_users();

echo json_encode($creator_users);