<?php include_once $_SERVER['DOCUMENT_ROOT']."/ugdms/def/index.php";?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="<?=url;?>css/style.css">
    <link rel="stylesheet" href="<?=url;?>css/login.css">
    <title>Document Management System</title>
    <script>let appUrl = '<?=url;?>';</script>
  </head>
  <body>
    <div class="width-100 height-50 flex-column justify-content-center align-items-center">
        <div class="font-gotham center-text uppercase ug-color bold-text font-30">Document Management System</div>
    </div>
    <div class="width-100 height-50 flex-column justify-content-center align-items-center ug-bg">
        <div class="width-50 width-l-70 width-m-70 width-s-90">
            <div class="acc-btn-holder">
                <div class="col-5 col-m-12 col-s-12 padding-all-10"><button class="adminBtn">Admin</button></div>
                <div class="col-5 col-m-12 col-s-12 padding-all-10"><button class="userBtn">User</button></div>
            </div>
        </div>
    </div>

    <script src="<?=url;?>script/preload.js"></script>
  </body>
</html>