<?php
if (!empty($_GET['id_reset'])) {
    $id = $_GET['id_reset'];
}
if (!empty($_GET['error'])) {
    $recover_error = $_GET['error'];
}

if (strlen($id) === 13) {
    ?><html>
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
                <a class="a_title"><p class="title_text">SHARE</p></a>
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
                        <p style="margin-top: 35px; margin-bottom: 20px;">Reset your password</p>
                    </div>
                    <div id="alert-box error" class="alert-message error-message invisible">
                        <p style="margin: 0; padding: 0"><strong>Error : </strong><?= $recover_error?></p>
                    </div>
                    <form class="sign-in_form" action="back/account_changes.php" method="POST">
                        <input class="form_input" type="password" name="new_pwd" placeholder="New password" required>
                        <input class="form_input" type="password" name="check" placeholder="Repeat new password" required>
                        <input type="hidden" name="id_reset" value="<?= $id?>">
                        <button class="sign-in_button">Reset</button>
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
<?php } else {
    header("Location: index.php?error=access_denied");
}?>