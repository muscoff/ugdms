<?php include_once $_SERVER['DOCUMENT_ROOT']."/ugdms/def/index.php"; ?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <!-- https://developer.mozilla.org/en-US/docs/Web/HTTP/CSP -->
    <meta http-equiv="Content-Security-Policy" content="script-src 'self'">
    <meta http-equiv="X-Content-Security-Policy" content="script-src 'self'">
    <link rel="stylesheet" href="<?=url;?>css/style.css">
    <link rel="stylesheet" href="<?=url;?>css/login.css">
    <title>Document Management System</title>
  </head>
  <body class="ug-bg">
    <div class="width-100 height-50 flex-column justify-content-center align-items-center white-bg">
        <div class="font-gotham center-text uppercase ug-color bold-text font-30">Document Management System</div>
    </div>
    <div class="ug-bg white-text">
        <div class="padding-all-20"></div>
        <div class="center-text font-text font-30 font-gotham">Create Super Admin Account</div>
        <div class="padding-all-20"></div>
        <div class="width-30 width-l-50 width-m-70 width-s-90 margin-auto">
            <form class="userAccountForm">
                <div>
                    <label class="font-gotham">Fullname</label>
                    <input type="text" placeholder="Enter Full name..">
                </div><br>
                <div>
                    <label class="font-gotham">Username</label>
                    <input type="text" required placeholder="Enter username">
                </div><br>
                <div>
                    <label class="font-gotham">Password</label>
                    <input type="password" required placeholder="Enter password">
                </div><br>
                <div>
                    <label class="font-gotham">Confirm Password</label>
                    <input type="password" required placeholder="Confirm password">
                </div><br>
                <div id="error" class="font-gotham red-text padding-all-5"></div><br>
                <div>
                    <button type="submit" class="adminBtn">Create Super Account</button>
                </div>
            </form>
        </div>
        <div class="padding-all-20"></div>
        <div class="padding-all-20"></div>
    </div>
  </body>
</html>
