<?php
header('Access-Control-Allow-Origin: *');
header('Content-type: Application/json');
include_once $_SERVER['DOCUMENT_ROOT']."/ugdms/def/index.php";
include_once SERVER."db/conn.php";
include_once SERVER."models/Admin.php";
include_once SERVER."libraries/lib.php";

// initialize DB
$DBH = new DB();
$db = $DBH->connect();

// Initialize Admin
$admin = new Admin($db);

if(isset($_GET['id'])){
    if(isset($_GET['view'])){
        $view = sanitize_text($_GET['view']); $view = (int)$view;
        $view = $view == 1 ? 0 : 1;

        $id = sanitize_text($_GET['id']); $id = (int)$id;
        $admin->view = $view; $admin->id = $id;

        if($admin->updateView()){
            echo json_encode(array('status'=>true));
        }else{
            echo json_encode(array('status'=>false));
        }
    }else if(isset($_GET['download'])){
        $download = sanitize_text($_GET['download']); $download = (int)$download;
        $download = $download == 1 ? 0 : 1;

        $id = sanitize_text($_GET['id']); $id = (int)$id;
        $admin->id = $id; $admin->download = $download;

        if($admin->updateDownload()){
            echo json_encode(array('status'=>true));
        }else{
            echo json_encode(array('status'=>false));
        }
    }else if(isset($_GET['upload'])){
        $upload = sanitize_text($_GET['upload']); $upload = (int)$upload;
        $upload = $upload == 1 ? 0 : 1;

        $id = sanitize_text($_GET['id']); $id = (int)$id;
        $admin->id = $id; $admin->upload = $upload;

        if($admin->updateUpload()){
            echo json_encode(array('status'=>true));
        }else{
            echo json_encode(array('status'=>false));
        }
    }else if(isset($_GET['delete'])){
        $delete = sanitize_text($_GET['delete']); $delete = (int)$delete;
        $delete = $delete == 1 ? 0 : 1;

        $id = sanitize_text($_GET['id']); $id = (int)$id;
        $admin->id = $id; $admin->delete = $delete;

        if($admin->updateDeleteDoc()){
            echo json_encode(array('status'=>true));
        }else{
            echo json_encode(array('status'=>false));
        }
    }else{
        die();
    }
}else{
    die();
}