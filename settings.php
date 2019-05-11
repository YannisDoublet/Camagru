<?php
include $_SERVER['DOCUMENT_ROOT'].'/config/database.php';
session_start();
if (empty($_SESSION['logued_user'])) {
    session_destroy();
    header("Location: index.php?error=access_denied");
}
if (!empty($_GET['error'])) {
    $error = $_GET['error'];
}
else if (!empty($_GET['success'])) {
    $success = $_GET['success'];
}
try {
    $DB = new PDO($DB_DSN.";dbname=".$DB_NAME, $DB_USER, $DB_PASSWORD);
    $stmt = $DB->prepare("SELECT `comments_notify`, `likes_notify` FROM user_info WHERE acc_id=?");
    $stmt->execute([$_SESSION['id']]);
    $user = $stmt->fetch();
    if (!empty($user)) {
        $fetch['likes'] = intval($user['likes_notify']) === 1 ? 1 : 0;
        $fetch['coms'] = intval($user['comments_notify']) === 1 ? 1 : 0;
    }
} catch (PDOException $e) {
    header("Location: ../settings.php?error=Database error :(");
}?>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Share - Settings</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="style/settings_style.css" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    <link rel="icon" href="assets/photography-icon-png-2382.png" type="image/x-icon">
    <script async src="front/settings.js"></script>
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
            <div class="settings">
                <div class="forms_header">
                    <p style="margin-top: 35px; margin-bottom: 20px;"><?= $_SESSION['username']."'s "?>Settings</p>
                </div>
                <div id="alert-box error" class="alert-message error-message invisible">
                    <p style="margin: 0; padding: 0"><strong>Error : </strong><?= $error?></p>
                </div>
                <div id="alert-box success" class="alert-message success-message invisible">
                    <p style="margin: 0; padding: 0"><strong>Success : </strong><?= $success?></p>
                </div>
                <div>
                    <p style="margin-bottom: 5px; border-radius: 5px" class="settings_header"
                       onclick="toggleInfo()">Personal information</p>
                    <div id="info" class="invisible">
                        <form class="settings_form" method="POST" action="back/account_changes.php">
                            <input name="user" class="form_input" type="text" placeholder="Change your username">
                            <input name="email" class="form_input" type="text" placeholder="Change your email">
                            <input class="settings_button" type="submit" value="Change">
                        </form>
                    </div>
                    <p style="margin: 3px 0 5px 0; border-radius: 5px" class="settings_header"
                       onclick="toggleReset()">Password Reset</p>
                    <div id ="reset" class="invisible">
                        <form class="settings_form" method="POST" action="back/account_changes.php">
                            <input name="pwd" style="margin-top: 15px" class="form_input" type="password" placeholder="New password">
                            <input name="pwd_check" class="form_input" type="password" placeholder="Repeat password">
                            <input class="settings_button" type="submit" value="Change">
                        </form>
                    </div>
                    <p style="margin: 0 0 5px 0; border-radius: 5px" class="settings_header" onclick="togglePreferences()">Preferences</p>
                    <div id="pref" class="invisible">
                        <form class="settings_form" method="POST" action="back/account_changes.php">
                            <div class="checkbox_container">
                                <input name="likes" style="margin: 10px" class="checkbox_input" type="checkbox" <?php if ($fetch['likes'] === 1) { echo "checked"; }?>><p class="checkbox_info">Notify me on likes !</p>
                            </div>
                            <div class="checkbox_container">
                                <input name="coms" style="margin: 10px" class="checkbox_input" type="checkbox" <?php if ($fetch['coms'] === 1) { echo "checked"; }?>><p class="checkbox_info">Notify me on comments !</p>
                            </div>
                            <input type="hidden" name="notify" value="1">
                            <input class="settings_button" type="submit" value="Change">
                        </form>
                    </div>
                    <p style="margin: 3px 0 5px 0; border-radius: 5px" class="settings_header"
                       onclick="toggleDelete()">Delete your account</p>
                    <div id ="delete" class="invisible">
                        <form class="settings_form" method="POST" action="back/account_changes.php">
                            <input name="del" class="settings_button" type="submit" value="Delete">
                        </form>
                    </div>
                </div>
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