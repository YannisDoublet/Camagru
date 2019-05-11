<?php
require_once("back/user_management.php");
session_start();
if (!empty($_SESSION['logued_user'])) {
    header("Location: ../index.php?error=access_denied");
    exit;
}
else {
    session_abort();
}
if (!empty($_GET['error'])) {
    $error = $_GET['error'];
}
else if (!empty($_GET['account'])) {
    $success = $_GET['account'];
}
$firstname = $_POST['firstname'];
$lastname = $_POST['lastname'];
$email = $_POST['email'];
$username = $_POST['username'];
$user = $_POST['user'];
?>
<html>
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Share - Sign up</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" type="text/css" media="screen" href="style/sign_up_style.css" />
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
        <link rel="icon" href="assets/photography-icon-png-2382.png" type="image/x-icon">
        <script async src="front/sign_up.js"></script>
    </head>
    <body>
        <div class="grid-container">
            <header class="header">
                <div class="title">
                    <a class="a_title" href="/"><p class="title_text">SHARE</p></a>
                </div>
                <div class="navbar">
                    <p class="slogan">Post amazing pictures</p>
                    <p class="slogan">Follow your friends</p>
                    <p class="slogan">Share with the world</p>
                </div>
            </header>
            <main class="content">
                <div class="forms">
                    <div class="sign-in">
                        <div class="forms_header">
                            <p style="margin-top: 35px; margin-bottom: 20px;">Already a member ?</p>
                        </div>
                        <div id="alert-box error" class="alert-message error-message invisible">
                            <p style="margin: 0; padding: 0"><strong>Error : </strong><?= $error?></p>
                        </div>
                        <div id="alert-box success" class="alert-message success-message invisible">
                            <p style="margin: 0; padding: 0"><strong>Success : </strong><?= $success?></p>
                        </div>
                        <form class="sign-in_form" action="back/user_management.php" method="POST">
                                <input class="form_input" type="text" name="user" placeholder="Username or Email" value="<?= $user?>" required>
                                <input class="form_input" type="password" name="psw" placeholder="Password" required>
                                <button class="sign-in_button">Sign In</button>
                        </form>
                        <div class="account_management">
                            <a class="recovery_option" href="account_recovery.php"><p style="padding-left: 10px;">Forgot your password or your confirmation email ?</p></a>
                        </div>
                    </div>
                    <div class="sign-up">
                        <div class="forms_header">
                            <p style="margin-top: 35px; margin-bottom: 20px;">Create your account !</p>
                        </div>
                        <form class="sign-up_form" action="back/user_management.php" method="POST">
                            <input class="form_input" type="text" name="firstname" placeholder="Firstname" required pattern="^[a-zA-Z]+$" value="<?= $firstname?>">
                            <input class="form_input" type="text" name="lastname" placeholder="Lastname" required pattern="^[a-zA-Z]+$" value="<?= $lastname?>">
                            <input class="form_input" type="email" name="email" placeholder="Email" required value="<?= $email?>">
                            <input class="form_input" type="text" name="username" placeholder="Username" required pattern="^[a-zA-Z0-9]{1,10}$" value="<?= $username?>">
                            <input class="form_input" type="password" name="psw" placeholder="Password" required pattern="^.{8,40}$">
                            <input class="form_input" type="password" name="check-psw" placeholder="Repeat password" required pattern="^.{8,40}$">
                            <button class="sign-up_button">Sign Up</button>
                        </form>
                    </div>
                </div>
            </main>
            <footer class="footer">
                <div class="copyright">&copy; 2019 Yannis Doublet</div>
                <div class="signature">SHARE</div>
            </footer>
        </div>
    </body>
</html>