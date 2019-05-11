<?php
if (!empty($_POST['user']) && !empty($_POST['psw'])) {
    // Check if user is connected
    session_start();
    if (!empty($_SESSION)) {
        header('Location: ../index.php?error=already_connected');
    } else {
        session_destroy();
        sign_in($_POST['user'], $_POST['psw']);
    }
} else if (!empty($_POST['firstname']) && !empty($_POST['lastname']) && !empty($_POST['email'])
        && !empty($_POST['username']) && !empty($_POST['psw']) && !empty($_POST['check-psw'])) {
    session_start();
    if (!empty($_SESSION)) {
        header('Location: ../index.php?error=already_connected');
    } else {
        session_destroy();
        sign_up();
    }
} else if (!empty($_GET['confirm_code'])) {
    confirm($_GET['confirm_code']);
} else if (!empty($_POST['resend_email'])) {
    resend_confirm($_POST['resend_email']);
} else if (!empty($_POST['recover_email'])) {
    recover_account($_POST['recover_email']);
}

function    recover_account($email) {
    include $_SERVER['DOCUMENT_ROOT'].'/config/database.php';
    try {
        $DB = new PDO($DB_DSN . ";dbname=" . $DB_NAME, $DB_USER, $DB_PASSWORD);
        $stmt = $DB->prepare("SELECT * FROM user_info WHERE email=?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        if (!empty($user)) {
            if ($user['email'] === $email) {
                $from = "no-reply@camagru.com";
                mail($email, "Reset your password",
                    "Hi ! You asked for a password reset, please click this link to proceed : http://localhost:8080/password_recovery.php?id_reset=".$user['acc_id'], "From: ".$from);
                header("Location: ../sign_up.php?account=Please check your mail to reset your password !");
            } else {
                header("Location: ../account_recovery.php?recover=Invalid email !");
            }
        } else {
            header("Location: ../account_recovery.php?recover=This email is not registered !");
        }
    }
    catch (PDOException $e) {
        header("Location: ../account_recovery.php?recover=Database error :(");
    }
}

function    resend_confirm($email) {
    include $_SERVER['DOCUMENT_ROOT'].'/config/database.php';
    try {
        $DB = new PDO($DB_DSN . ";dbname=" . $DB_NAME, $DB_USER, $DB_PASSWORD);
        $stmt = $DB->prepare("SELECT * FROM user_info WHERE email=?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        if (!empty($user)) {
            if ($user['email'] === $email) {
                $from = "no-reply@camagru.com";
                mail($email, "Resend confirmation",
                    "Welcome to Share please confirm your account by clicking this link http://localhost:8080/sign_up.php?confirm_code=".$user['acc_id'], "From: ".$from);
                header("Location: ../sign_up.php?account=Email resend !");
            } else {
                header("Location: ../account_recovery.php?email=Invalid email !");
            }
        } else {
            header("Location: ../account_recovery.php?email=This email is not registered !");
        }
    }
    catch (PDOException $e) {
        header("Location: ../account_recovery.php?email=Database error :(");
    }
}

function    confirm($code) {
    include $_SERVER['DOCUMENT_ROOT'].'/config/database.php';
    try {
        $DB = new PDO($DB_DSN . ";dbname=" . $DB_NAME, $DB_USER, $DB_PASSWORD);
        $stmt = $DB->prepare("SELECT * FROM user_info WHERE acc_id=?");
        $stmt->execute([$code]);
        $user = $stmt->fetch();
        if (!empty($user)) {
            if (intval($user['validate']) === 1) {
                header("Location: ../sign_up.php?account=Account already validated !");
                exit ;
            }
            try {
                $stmt = $DB->prepare("UPDATE user_info SET validate=? WHERE acc_id=?");
                $stmt->execute([1, $code]);
                header("Location: ../sign_up.php?account=Account validated !");
            }
            catch (PDOException $e) {
                header("Location: ../sign_up.php?error=Database error :(");
            }
        }
    }
    catch (PDOException $e) {
        header("Location: ../sign_up.php?error=Database error :(");
    }
}

function    resubmit_info($error, $dodge) {
    $resub = array();
    foreach ($_POST as $key => $value) {
        if ($key === $dodge || $key === "psw" || $key === "check-psw") {
            ;
        }
        else {
            $resub[$key] = $value;
        }
    }?>
    <html>
        <form style="display: none;" id="resub" action="../sign_up.php?error=<?= $error; ?>" method="POST">
            <?php foreach ($resub as $key => $value){?>
                <input type="hidden" name="<?= $key?>" value="<?= $value?>">
            <?php }?>
        </form>
        <script>
            document.getElementById('resub').submit();
        </script>
       </html><?php
}

function    sign_in($user, $psw) {
    include $_SERVER['DOCUMENT_ROOT'].'/config/database.php';
    try {
        $DB = new PDO($DB_DSN.";dbname=".$DB_NAME, $DB_USER, $DB_PASSWORD);
        $stmt = $DB->prepare("SELECT username, email, `password`, validate, firstname, acc_id
                FROM user_info WHERE username=? OR email=?");
        $stmt->execute([$user, $user]);
        $log = $stmt->fetch();
        if (!empty($log)) {
            if (intval($log['validate']) === 1) {
                if (password_verify($psw, $log['password'])) {
                    session_start();
                    $_SESSION['logued_user'] = $log['firstname'];
                    $_SESSION['username'] = $log['username'];
                    $_SESSION['id'] = $log['acc_id'];
                    header("Location: ../index.php?sign_in=success");
                }
                else {
                    resubmit_info("Invalid password !", "psw");
                    exit;
                }
            }
            else {
                header("Location: ../sign_up.php?error=Unvalidated account !");
            }
        }
        else {
            header("Location: ../sign_up.php?error=Invalid username or password");
        }
    }
    catch (PDOException $e) {
        header("Location: ../sign_up.php?error=Database error :(");
    }
}

function    sign_up() {
    include $_SERVER['DOCUMENT_ROOT'].'/config/database.php';
    date_default_timezone_set("Europe/Paris");
    if (!preg_match("/^[a-zA-Z]+$/", $_POST['firstname'])) {
        resubmit_info("Invalid firstname !", "firstname");
        exit;
    }
    if (!preg_match("/^[a-zA-Z]+$/", $_POST['lastname'])) {
        resubmit_info("Invalid lastname !", "lastname");
        exit;
    }
    if (!preg_match("/^[a-zA-Z0-9.!#$%&'*+\=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)+$/", $_POST['email'])) {
        resubmit_info("Invalid email !", "email");
        exit;
    }
    if (!preg_match("/^[a-zA-Z0-9]{5,10}$/", $_POST['username'])) {
        resubmit_info("Invalid username !", "username");
        exit;
    }
    if (!preg_match("/^.{8,40}$/", $_POST['psw']) || !preg_match("/^.{8,40}$/", $_POST['check-psw']) || $_POST['psw'] !== $_POST['check-psw']) {
        resubmit_info("Invalid password !", "");
        exit;
    }
    try {
        $DB = new PDO($DB_DSN.";dbname=".$DB_NAME, $DB_USER, $DB_PASSWORD);
        $stmt = $DB->prepare("SELECT username, email FROM user_info WHERE username=? OR email=?");
        $stmt->execute([$_POST['username'], $_POST['email']]);
        $user = $stmt->fetch();
        if (empty($user)) {
            $psw = password_hash($_POST['psw'], PASSWORD_BCRYPT);
            $acc_id = uniqid();
            try {
                if (file_exists("../users") && !file_exists("../users/".$_POST['username'])) {
                    $stmt = $DB->prepare("INSERT INTO user_info (firstname, lastname, email,
                      username, `password`, photo_uploaded, comment_uploaded, acc_id, validate, comments_notify, likes_notify, creation_time)
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                    $stmt->execute([$_POST['firstname'], $_POST['lastname'], $_POST['email'],
                        $_POST['username'], $psw, 0, 0, $acc_id, 0, 1, 1, date('Y-m-d H:i:s')]);
                    mkdir("../users/".$_POST['username']);
                    copy("../assets/banner.jpg", "../users/".$_POST['username']."/banner.jpg");
                    copy("../assets/profile_pic.jpg", "../users/".$_POST['username']."/profile_pic.jpg");
                    $from = "no-reply@camagru.com";
                    mail($_POST['email'], "Confirm your account",
                        "Welcome to Share please confirm your account by clicking this link http://".$_SERVER['HTTP_HOST']."/sign_up.php?confirm_code=".$acc_id, "From: ".$from);
                    header("Location: ../email_confirmation.php");
                }
                else {
                    header("Location: ../sign_up.php?error=Internal error :(");
                    exit ;
                }
            }
            catch (PDOException $e) {
                header("Location: ../sign_up.php?error=Database error :(");
            }
        }
        else {
            if ($user['username'] === $_POST['username']) {
                resubmit_info("Username already taken !", "username");
                exit;
            }
            else if ($user['email'] === $_POST['email']) {
                resubmit_info("Email already taken !", "email");
                exit;
            }
            else if ($user['phonenumber'] === $_POST['phone']) {
                resubmit_info("Phonenumber already taken !", "phone");
                exit;
            }
        }
    }
    catch (PDOException $e) {
       header("Location: ../sign_up.php?error=Database error :(");
    }
}