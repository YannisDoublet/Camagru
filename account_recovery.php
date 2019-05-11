<?php
if (!empty($_GET['email'])) {
    $email_error = $_GET['email'];
}
else if (!empty($_GET['recover'])) {
    $recover_error = $_GET['recover'];
}
?>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Camagru</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="style/account_recovery_style.css" />
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
                    <p style="margin-top: 35px; margin-bottom: 20px;">Recover your account</p>
                </div>
                <div id="alert-box error recover" class="alert-message error-message invisible">
                    <p style="margin: 0; padding: 0"><strong>Error : </strong><?= $recover_error?></p>
                </div>
                <form class="sign-in_form" action="back/user_management.php" method="POST">
                    <input class="form_input" type="text" name="recover_email" placeholder="Email" required>
                    <button class="sign-in_button">Recover</button>
                </form>
                <div class="account_management">
                    <a class="recovery_option" href="sign_up.php"><p style="padding-left: 10px;">No need to recover ?</p></a>
                </div>
            </div>
            <div class="sign-in">
                <div class="forms_header">
                    <p style="margin-top: 35px; margin-bottom: 20px;">Send your verification email</p>
                </div>
                <div id="alert-box error email" class="alert-message error-message invisible">
                    <p style="margin: 0; padding: 0"><strong>Error : </strong><?= $email_error?></p>
                </div>
                <form class="sign-in_form" action="back/user_management.php" method="POST">
                    <input class="form_input" type="text" name="resend_email" placeholder="Email" required>
                    <button class="sign-in_button">Send</button>
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
