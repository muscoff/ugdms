<?php
header('Access-Control-Allow-Origin: *');
header('Content-type: Application/json');

include_once $_SERVER['DOCUMENT_ROOT']."/ugdms/def/index.php";
include_once SERVER."db/conn.php";
include_once SERVER."models/Offices.php";
include_once SERVER."models/Users.php";
include_once SERVER."models/Group.php";
include_once SERVER."models/Folder.php";
include_once SERVER."models/Document.php";
include_once SERVER."libraries/lib.php";

// Initialize DB
$DBH = new DB();
$db = $DBH->connect();

// Initialize Offices
$offices = new Offices($db);

// Initialize Users
$users = new Users($db);

// Initialize Group
$group = new Group($db);

// Initialize Folder
$folder = new Folder($db);

// Initialize Document
$document = new Document($db);

if(isset($_POST['id']) & isset($_POST['admin']) & isset($_POST['new_admin']) & !empty($_POST['id']) & !empty($_POST['admin']) & !empty($_POST['new_admin'])){
    $offices->admin = sanitize_text($_POST['new_admin']);
    $offices->id = sanitize_text($_POST['id']);
    $offices->parent = sanitize_text($_POST['id']);

    $users->string = sanitize_text($_POST['new_admin']);
    $users->creator = sanitize_text($_POST['admin']);

    $group->string = sanitize_text($_POST['new_admin']);
    $group->creator = sanitize_text($_POST['admin']);

    $folder->string = sanitize_text($_POST['new_admin']);
    $folder->creator = sanitize_text($_POST['admin']);

    $document->string = sanitize_text($_POST['new_admin']);
    $document->parent = sanitize_text($_POST['admin']);

    // Check if admin have any users
    $admin_users = json_decode(file_get_contents(url."api/users/creator_users.php?creator=".$users->creator),true);
    $count = count($admin_users);

    $admin_group = json_decode(file_get_contents(url."api/group/creator_group.php?creator=".$group->creator), true);
    $groupCount = count($admin_group);

    $admin_folder = json_decode(file_get_contents(url."api/folder/creator_folder.php?creator=".$folder->creator), true);
    $folderCount = count($admin_folder);

    $admin_document = json_decode(file_get_contents(url."api/document/creator_document.php?creator=frank"),true);
    $docCount = count($admin_document);

    if($count > 0 & $groupCount > 0 & $folderCount > 0 & $docCount > 0){
        if($offices->handover() & $users->handover() & $group->handover() & $folder->handover() & $document->handover()){
            echo json_encode(array('status'=>true));
        }else{
            echo json_encode(array('status'=>false, 'msg'=>'Failed to handover office hereeeee'));
        }
    }else if($count > 0 & $groupCount > 0 & $folderCount > 0 & $docCount == 0){
        if($offices->handover() & $users->handover() & $group->handover() & $folder->handover()){
            echo json_encode(array('status'=>true));
        }else{
            echo json_encode(array('status'=>false, 'msg'=>'Failed to handover office hereeeee'));
        }
    }else if($count > 0 & $groupCount > 0 & $folderCount == 0 & $docCount > 0){
        if($offices->handover() & $users->handover() & $group->handover() & $document->handover()){
            echo json_encode(array('status'=>true));
        }else{
            echo json_encode(array('status'=>false, 'msg'=>'Failed to handover office hereeeee'));
        }
    }else if($count > 0 & $groupCount == 0 & $folderCount > 0 & $docCount > 0){
        if($offices->handover() & $users->handover() & $folder->handover() & $document->handover()){
            echo json_encode(array('status'=>true));
        }else{
            echo json_encode(array('status'=>false, 'msg'=>'Failed to handover office hereeeee'));
        }
    }else if($count == 0 & $groupCount > 0 & $folderCount > 0 & $docCount > 0){
        if($offices->handover() & $group->handover() & $folder->handover() & $document->handover()){
            echo json_encode(array('status'=>true));
        }else{
            echo json_encode(array('status'=>false, 'msg'=>'Failed to handover office hereeeee'));
        }
    }else if($count > 0 & $groupCount > 0 & $folderCount == 0 & $docCount == 0){
        if($offices->handover() & $users->handover() & $group->handover()){
            echo json_encode(array('status'=>true));
        }else{
            echo json_encode(array('status'=>false, 'msg'=>'Failed to handover office hereeeee'));
        }
    }else if($count > 0 & $groupCount == 0 & $folderCount > 0 & $docCount == 0){
        if($offices->handover() & $users->handover() & $folder->handover()){
            echo json_encode(array('status'=>true));
        }else{
            echo json_encode(array('status'=>false, 'msg'=>'Failed to handover office hereeeee'));
        }
    }else if($count == 0 & $groupCount > 0 & $folderCount > 0 & $docCount == 0){
        if($offices->handover() & $group->handover() & $folder->handover()){
            echo json_encode(array('status'=>true));
        }else{
            echo json_encode(array('status'=>false, 'msg'=>'Failed to handover office hereeeee'));
        }
    }else if($count > 0 & $groupCount == 0 & $folderCount == 0 & $docCount > 0){
        if($offices->handover() & $users->handover() & $document->handover()){
            echo json_encode(array('status'=>true));
        }else{
            echo json_encode(array('status'=>false, 'msg'=>'Failed to handover office hereeeee'));
        }
    }else if($count == 0 & $groupCount > 0 & $folderCount == 0 & $docCount > 0){
        if($offices->handover() & $group->handover() & $document->handover()){
            echo json_encode(array('status'=>true));
        }else{
            echo json_encode(array('status'=>false, 'msg'=>'Failed to handover office hereeeee'));
        }
    }else if($count == 0 & $groupCount == 0 & $folderCount > 0 & $docCount > 0){
        if($offices->handover() & $folder->handover() & $document->handover()){
            echo json_encode(array('status'=>true));
        }else{
            echo json_encode(array('status'=>false, 'msg'=>'Failed to handover office hereeeee'));
        }
    }else if($count > 0 & $groupCount == 0 & $folderCount == 0 & $docCount == 0){
        if($offices->handover() & $users->handover()){
            echo json_encode(array('status'=>true));
        }else{
            echo json_encode(array('status'=>false, 'msg'=>'Failed to handover office hereeeee'));
        }
    }else if($count == 0 & $groupCount > 0 & $folderCount == 0 & $docCount == 0){
        if($offices->handover() & $group->handover()){
            echo json_encode(array('status'=>true));
        }else{
            echo json_encode(array('status'=>false, 'msg'=>'Failed to handover office hereeeee'));
        }
    }else if($count == 0 & $groupCount == 0 & $folderCount > 0 & $docCount == 0){
        if($offices->handover() & $folder->handover()){
            echo json_encode(array('status'=>true));
        }else{
            echo json_encode(array('status'=>false, 'msg'=>'Failed to handover office hereeeee'));
        }
    }else if($count == 0 & $groupCount == 0 & $folderCount == 0 & $docCount > 0){
        if($offices->handover() & $document->handover()){
            echo json_encode(array('status'=>true));
        }else{
            echo json_encode(array('status'=>false, 'msg'=>'Failed to handover office hereeeee'));
        }
    }else{
        if($offices->handover()){
            echo json_encode(array('status'=>true));
        }else{
            echo json_encode(array('status'=>false, 'msg'=>'Failed to handover office'));
        }
    }

    // if($count > 0 & $groupCount > 0 & $folderCount > 0){
        // if($offices->handover() & $users->handover() & $group->handover() & $folder->handover()){
        //     echo json_encode(array('status'=>true));
        // }else{
        //     echo json_encode(array('status'=>false, 'msg'=>'Failed to handover office hereeeee'));
        // }
    // }else if($count > 0 & $groupCount > 0 & $folderCount == 0){
    //     if($offices->handover() & $users->handover() & $group->handover()){
    //         echo json_encode(array('status'=>true));
    //     }else{
    //         echo json_encode(array('status'=>false, 'msg'=>'Failed to handover office hereeeee'));
    //     }
    // }else if($count > 0 & $groupCount == 0 & $folderCount == 0){
    //     if($offices->handover() & $users->handover()){
    //         echo json_encode(array('status'=>true));
    //     }else{
    //         echo json_encode(array('status'=>false, 'msg'=>'Failed to handover office hereeeee'));
    //     }
    // }else if($count == 0 & $groupCount > 0 & $folderCount > 0){
    //     if($offices->handover() & $group->handover() & $folder->handover()){
    //         echo json_encode(array('status'=>true));
    //     }else{
    //         echo json_encode(array('status'=>false, 'msg'=>'Failed to handover office hereeeee'));
    //     }
    // }else if($count == 0 & $groupCount == 0 & $folderCount > 0){
    //     if($offices->handover() & $folder->handover()){
    //         echo json_encode(array('status'=>true));
    //     }else{
    //         echo json_encode(array('status'=>false, 'msg'=>'Failed to handover office hereeeee'));
    //     }
    // }else if($count == 0 & $groupCount > 0 & $folderCount == 0){
    //     if($offices->handover() & $group->handover()){
    //         echo json_encode(array('status'=>true));
    //     }else{
    //         echo json_encode(array('status'=>false, 'msg'=>'Failed to handover office hereeeee'));
    //     }
    // }else if($count > 0 & $groupCount == 0 & $folderCount > 0){
    //     if($offices->handover() & $users->handover() & $folder->handover()){
    //         echo json_encode(array('status'=>true));
    //     }else{
    //         echo json_encode(array('status'=>false, 'msg'=>'Failed to handover office hereeeee'));
    //     }
    // }else{
        // if($offices->handover()){
        //     echo json_encode(array('status'=>true));
        // }else{
        //     echo json_encode(array('status'=>false, 'msg'=>'Failed to handover office'));
        // }
    // }
}else{
    echo json_encode(array('status'=>false, 'msg'=>'Id, admin and new admin is required'));
}