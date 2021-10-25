<?php
header('Access-Control-Allow-Origin: *');
header('Content-type: Application/json');

include_once $_SERVER['DOCUMENT_ROOT']."/ugdms/def/index.php";
include_once SERVER."db/conn.php";
include_once SERVER."models/Document.php";
include_once SERVER."libraries/lib.php";

// Initialize DB
$DBH = new DB();
$db = $DBH->connect();

// Initialize Document
$document = new Document($db);

if(isset($_POST['path']) & !empty($_POST['path'])){
    $document->name = sanitize_text($_POST['name']);
    $document->centre = sanitize_text($_POST['centre']);
    $document->exams_date = sanitize_text($_POST['date']);
    $document->creator = sanitize_text($_POST['creator']);
    $document->parent = sanitize_text($_POST['parent']);
    $document->office = sanitize_text($_POST['office']);
    $document->file_path = sanitize_text($_POST['path']);
    $document->semester = sanitize_text($_POST['semester']);
    $path = ltrim($document->file_path, '/');

    // $arr = array(
    //     'name'=>$document->name,
    //     'centre'=>$document->centre,
    //     'exams_date'=>$document->exams_date,
    //     'creator'=>$document->creator,
    //     'office'=>$document->office,
    //     'path'=>$document->file_path,
    //     'parent'=>$document->parent,
    //     'semester'=>$document->semester,
    // );

    // echo json_encode($arr);

    if(isset($_FILES['file']['name']) & !empty($_FILES['file']['name'])){
        if(!$_FILES['file']['error']){
            if($_FILES['file']['type'] == 'application/pdf'){
                $ext = $_FILES['file']['name']; $ext = explode('.', $ext); $ext = end($ext);
                $filename = uniqid().rand(1,10000).'.'.$ext;
                $document->link = url.$path."/".$filename;
                $server = SERVER.$path."/".$filename;
                if(move_uploaded_file($_FILES['file']['tmp_name'], $server)){
                    if($document->create()){
                        echo json_encode(array('status'=>true, 'msg'=>'File upload. Data Recorded'));
                    }else{
                        unlink($server);
                        echo json_encode(array('status'=>false, 'msg'=>'Failed to record file upload'));
                    }
                }else{
                    echo json_encode(array('status'=>false, 'Failed to upload file'));
                }
            }else{
                echo json_encode(array('msg'=>'File must be a pdf', 'status'=>false));
            }
        }else{
            echo json_encode(array('msg'=>'Error: Check the file size', 'status'=>false));
        }
    }else{
        echo json_encode(array('status'=>false, 'msg'=>'File is possibly not attached'));
    }
}else{
    echo json_encode(array('status'=>false, 'msg'=>'undefined path name'));
}