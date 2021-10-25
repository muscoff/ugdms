<?php include_once $_SERVER['DOCUMENT_ROOT']."/ugdms/def/index.php"; ?>
<!DOCTYPE html>
<html>
  <head>
    <?php include_once SERVER."includes/meta.php"; ?>
  </head>
  <body class="ug-bg">
    <div class="width-100 height-50 flex-column justify-content-center align-items-center white-bg">
        <div class="font-gotham center-text uppercase ug-color bold-text font-30">Document Management System</div>
    </div>
    <div class="ug-bg white-text">
        <div class="padding-all-20"></div>
        <div class="center-text font-text font-30 font-gotham">Admin Login Page</div>
        <div class="padding-all-20"></div>
        <div class="width-30 width-l-50 width-m-70 width-s-90 margin-auto">
            <form class="userAccountForm">
                <div>
                    <label class="font-gotham">Username</label>
                    <input type="text" required placeholder="Enter username">
                </div><br>
                <div>
                    <label class="font-gotham">Password</label>
                    <input type="password" required placeholder="Enter password">
                </div><br>
                <div id="error" class="font-gotham red-text padding-all-5"></div><br>
                <div>
                    <button type="submit" class="adminBtn">Login</button>
                </div>
            </form>
        </div>
        <div class="padding-all-20"></div>
        <div class="padding-all-20"></div>
    </div>
    <script src="<?=url;?>api/dev/script.js"></script>
    <script src="<?=url;?>script/admin_login.js"></script>
  </body>
</html>
