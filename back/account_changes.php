<?php
session_start();

// Choose the right function dispatch the right informations.

if (!empty($_POST['id_reset']) && !empty($_POST['new_pwd']) && !empty($_POST['check'])) {
       reset_password( $_POST['id_reset'], $_POST['new_pwd'], $_POST['check']);
} else if (!empty($_POST['b64']) && !empty($_POST['pic'])) {
    change_picture($_POST['b64'], $_POST['pic']);
} else if (!empty($_POST['user']) || !empty($_POST['email'])) {
    foreach($_POST as $key => $value) {
        $changes[$key] = htmlspecialchars($value);
    }
    $changes['id'] = $_SESSION['id'];
    reset_info($changes);
} else if (!empty($_POST['pwd']) && !empty($_POST['pwd_check'])) {
    reset_password_login($_POST['pwd'], $_POST['pwd_check'], $_SESSION['id']);
} else if (!empty($_SESSION['id']) && !empty($_POST['notify'])) {
    $notify['likes'] = !empty($_POST['likes']) ? 1 : 0;
    $notify['coms'] = !empty($_POST['coms']) ? 1 : 0;
    notification($notify);
} else if (!empty($_SESSION['id']) && $_SESSION['username'] && !empty($_POST['del']) && $_POST['del'] === "Delete") {
    delete_account($_SESSION['id'], $_SESSION['username']);
} else {
    header('Location: ../settings.php?error=Invalid informations provided !');
}

// Create a profile or a banner picture.

function    change_picture($data, $pic) {
    $data = str_replace('data:image/png;base64', '', $data);
    $data = str_replace(' ', '+', $data);
    $data = base64_decode($data);
    $source_img = imagecreatefromstring($data);
    $file = '../users/'.$_SESSION['username'].'/'.$pic.'.jpg';
    imagejpeg($source_img, $file,75);
    imagedestroy($source_img);
    http_response_code(200);
}

// Delete a directory.

function empty_dir($target) {
    if (is_dir($target)){
        $files = glob($target . '*', GLOB_MARK);
        foreach($files as $file) {
            empty_dir($file);
        }
    } else if (is_file($target)) {
        unlink($target);
    }
}

// Delete your account.

function    delete_account($id, $user) {
    include $_SERVER['DOCUMENT_ROOT'].'/config/database.php';
    try {
        $DB = new PDO($DB_DSN.";dbname=".$DB_NAME, $DB_USER, $DB_PASSWORD);
        $stmt = $DB->prepare("DELETE FROM `user_info` WHERE acc_id=?");
        $stmt->execute([$id]);
        $stmt = $DB->prepare("DELETE FROM `img_info` WHERE acc_id=?");
        $stmt->execute([$id]);
        $stmt = $DB->prepare("DELETE FROM `comments_info` WHERE acc_id=?");
        $stmt->execute([$id]);
        $stmt = $DB->prepare("DELETE FROM `likes_info` WHERE acc_id=?");
        $stmt->execute([$id]);
        empty_dir('../users/'.$_SESSION['username']);
        rmdir('../users/'.$_SESSION['username']);
        $_SESSION = array();
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
        header("Location: ../index.php?account=logout_success");
    } catch (PDOException $e) {
        header("Location: ../settings.php?error=Database error :(");
    }
}

// Enable or disable email notification on likes and email

function    notification($notify) {
    include $_SERVER['DOCUMENT_ROOT'].'/config/database.php';
    try {
        $DB = new PDO($DB_DSN.";dbname=".$DB_NAME, $DB_USER, $DB_PASSWORD);
        $stmt = $DB->prepare("SELECT `comments_notify`, `likes_notify` FROM user_info WHERE acc_id=?");
        $stmt->execute([$_SESSION['id']]);
        $user = $stmt->fetch();
        if (!empty($user)) {
            if (intval($user['comments_notify']) !== intval($notify['coms'])
                || intval($user['likes_notify']) !== intval($notify['likes'])) {
                try {
                    $stmt = $DB->prepare("UPDATE user_info SET `comments_notify`=?, `likes_notify`=? WHERE acc_id=?");
                    $stmt->execute([$notify['coms'], $notify['likes'], $_SESSION['id']]);
                    header('Location: ../settings.php?success=Notification settings changed !');
                } catch (PDOException $e) {
                    header("Location: ../settings.php?error=Database error :(");
                }
            } else {
                header("Location: ../settings.php?error=Notification unchanged !");
            }
        }
    } catch (PDOException $e) {
        header("Location: ../settings.php?error=Database error :(");
    }
}

// Reset users password on settings page

function    reset_password_login($pwd, $check, $id) {
    include $_SERVER['DOCUMENT_ROOT'].'/config/database.php';
    if (!preg_match("/^.{8,40}$/", $pwd) || !preg_match("/^.{8,40}$/", $check) || $pwd !== $check) {
        header("Location: ../settings.php?error=Invalid password !");
        exit;
    }
    try {
        $DB = new PDO($DB_DSN.";dbname=".$DB_NAME, $DB_USER, $DB_PASSWORD);
        $stmt = $DB->prepare("SELECT `password` FROM user_info WHERE acc_id=?");
        $stmt->execute([$id]);
        $user = $stmt->fetch();
        if (!empty($user)) {
            try {
                $stmt = $DB->prepare("UPDATE user_info SET `password`=? WHERE acc_id=?");
                $stmt->execute([password_hash($pwd, PASSWORD_BCRYPT), $id]);
                header('Location: ../settings.php?success=Password changed !');
            } catch (PDOException $e) {
                header("Location: ../settings.php?error=Database error :(");
            }
        }
    } catch (PDOException $e) {
        header("Location: ../settings.php?error=Database error :(");
    }
}

// Reset the user username and email.

function    reset_info($changes) {
    include $_SERVER['DOCUMENT_ROOT'].'/config/database.php';
    $old = $_SESSION['username'];
    if (!empty($changes['email']) && !preg_match("/^[a-zA-Z0-9.!#$%&'*+\=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)+$/", $changes['email'])) {
        header("Location: ../settings.php?error=Email invalid !");
        exit;
    }
    if (!empty($changes['user']) && !preg_match("/^[a-zA-Z0-9]{5,10}$/", $changes['user'])) {
        header("Location: ../settings.php?error=Username invalid !");
        exit;
    }
    try {
        $DB = new PDO($DB_DSN.";dbname=".$DB_NAME, $DB_USER, $DB_PASSWORD);
        $stmt = $DB->prepare("SELECT username, email FROM user_info WHERE username=? OR email=?");
        $stmt->execute([$changes['user'], $changes['email']]);
        $user = $stmt->fetchAll();
        if (!empty($user)) {
            header("Location: ../settings.php?error=Username or email already taken !");
            exit ;
        }
    }  catch (PDOException $e) {
        header("Location: ../settings.php?error=Database error :(");
    }
    try {
        $DB = new PDO($DB_DSN.";dbname=".$DB_NAME, $DB_USER, $DB_PASSWORD);
        $stmt = $DB->prepare("SELECT acc_id, username, email FROM user_info WHERE acc_id=?");
        $stmt->execute([$changes['id']]);
        $user = $stmt->fetch();
        if (!empty($user)) {
            if ((!empty($changes['user']) && $changes['user'] === $user['username']) ||
                (!empty($changes['email']) && $changes['email'] === $user['email'])) {
                header("Location: ../settings.php?error=This username or email is already yours !");
                exit ;
            }
            if (!empty($changes['user']) && empty($changes['email'])) {
                try {
                    $stmt = $DB->prepare("UPDATE user_info SET username=? WHERE acc_id=?");
                    $stmt->execute([$changes['user'], $changes['id']]);
                    $_SESSION['username'] = $changes['user'];
                    try {
                        $stmt = $DB->prepare("UPDATE `img_info` SET `user`=? WHERE acc_id=?");
                        $stmt->execute([$changes['user'], $changes['id']]);
                    } catch (PDOException $e) {
                        header("Location: ../settings.php?error=Database error :(");
                    }
                    try {
                        $stmt = $DB->prepare("UPDATE `comments_info` SET `user`=? WHERE acc_id=?");
                        $stmt->execute([$changes['user'], $changes['id']]);
                        rename("../users/$old", "../users/$changes[user]");
                    } catch (PDOException $e) {
                        header("Location: ../settings.php?error=Database error :(");
                    }
                } catch (PDOException $e) {
                    header("Location: ../settings.php?error=Database error :(");
                }
            } else if (empty($changes['user']) && !empty($changes['email'])) {
                try {
                    $stmt = $DB->prepare("UPDATE user_info SET email=? WHERE acc_id=?");
                    $stmt->execute([$changes['email'], $changes['id']]);
                } catch (PDOException $e) {
                    header("Location: ../settings.php?error=Database error :(");
                }
            } else {
                try {
                    $stmt = $DB->prepare("UPDATE user_info SET username=?, email=? WHERE acc_id=?");
                    $stmt->execute([$changes['user'], $changes['email'], $changes['id']]);
                    $_SESSION['username'] = $changes['user'];
                    try {
                        $stmt = $DB->prepare("UPDATE `img_info` SET `user`=? WHERE acc_id=?");
                        $stmt->execute([$changes['user'], $changes['id']]);
                    } catch (PDOException $e) {
                        header("Location: ../settings.php?error=Database error :(");
                    }
                    try {
                        $stmt = $DB->prepare("UPDATE `comments_info` SET `user`=? WHERE acc_id=?");
                        $stmt->execute([$changes['user'], $changes['id']]);
                        rename("../users/$old", "../users/$changes[user]");
                    } catch (PDOException $e) {
                        header("Location: ../settings.php?error=Database error :(");
                    }
                } catch (PDOException $e) {
                    header("Location: ../settings.php?error=Database error :(");
                }
            }
            header('Location: ../settings.php?success=Information changed !');
            exit ;
        } else {
            header("Location: ../index.php?error=Incorrect id");
        }
    } catch (PDOException $e) {
        header("Location: ../settings.php?error=Database error :(");
    }
}

// Reset password without signing in.

function    reset_password($id, $new, $check) {
    include $_SERVER['DOCUMENT_ROOT'].'/config/database.php';
    if ($new === $check) {
        if (!preg_match("/^.{8,40}$/", $new)) {
            header("Location: ../password_recovery.php?id_reset=$id&error=Password too weak !");
            exit ;
        } else {
            try {
                $DB = new PDO($DB_DSN . ";dbname=" . $DB_NAME, $DB_USER, $DB_PASSWORD);
                $stmt = $DB->prepare("SELECT * FROM user_info WHERE acc_id=?");
                $stmt->execute([$id]);
                $user = $stmt->fetch();
                if (!empty($user)) {
                    try {
                        if (password_verify($new, $user['password'])) {
                            header("Location: ../sign_up.php?account=Well done you remember your password !");
                            exit ;
                        }
                        $psw = password_hash($new, PASSWORD_BCRYPT);
                        $stmt = $DB->prepare("UPDATE user_info SET password=? WHERE acc_id=?");
                        $stmt->execute([$psw, $id]);
                        header("Location: ../sign_up.php?account=Password reset !");
                    }
                    catch (PDOException $e) {
                        header("Location: ../password_recovery.php?id_reset=$id&error=Database error :(");
                    }
                } else {
                    header("Location: ../password_recovery.php?id_reset=$id&error=Wrong reset id !");
                }
            }
            catch (PDOException $e) {
                header("Location: ../password_recovery.php?id_reset=$id&error=Database error :(");
            }
        }
    } else {
        header("Location: ../password_recovery.php?id_reset=$id&error=Incorrect password !");
    }
}