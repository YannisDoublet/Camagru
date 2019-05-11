<?php
// Execute the right function

if (!empty($_POST['update']) && isset($_POST['scroll']) && $_POST['update'] === 'index') {
    // Check if user is connected
    session_start();
    if (empty($_SESSION)) {
        session_destroy();
    }
    update_index($_SESSION['id'], intval($_POST['scroll']));
} else if (!empty($_POST['update']) && $_POST['update'] === 'profile' || $_POST['update'] === 'old') {
    // Check if user is connected
    session_start();
    if (empty($_SESSION)) {
        session_destroy();
        exit;
    }
    update_profile($_SESSION['username'], $_SESSION['id']);
} else if (!empty($_POST['update']) && !empty($_POST['id']) && $_POST['update'] === 'erase') {
    // Check if user is connected
    session_start();
    if (empty($_SESSION)) {
        session_destroy();
        exit;
    }
    erase_picture($_POST['id'], $_SESSION['id']);
} else if (!empty($_POST['update']) && $_POST['update'] === 'counter') {
    update_counter();
} else if (!empty($_POST['update']) && $_POST['update'] === 'profile_counters') {
    // Check if user is connected
    session_start();
    if (empty($_SESSION)) {
        session_destroy();
        exit;
    }
    update_profile_counter($_SESSION['username']);
} else if (!empty($_POST['update']) && !empty($_POST['id']) && $_POST['update'] === 'likes') {
    // Check if user is connected
    session_start();
    if (empty($_SESSION)) {
        session_destroy();
        exit;
    }
    update_photos_likes($_SESSION['id'], $_SESSION['username'], $_POST['id']);
} else if (!empty($_POST['post']) && !empty($_POST['value']) && !empty($_POST['src'])
            && $_POST['post'] === 'comments') {
    // Check if user is connected
    session_start();
    if (empty($_SESSION)) {
        session_destroy();
        exit;
    }
    post_comments(htmlspecialchars($_POST['value']), $_POST['src'], $_SESSION['id'], $_SESSION['username']);
} else if (!empty($_POST['update']) && $_POST['update'] === 'comments') {
    fetch_comments();
}

// Insert comment on a photo and save it in the Database.

function    post_comments($value, $img_src, $acc_id, $user) {
    include $_SERVER['DOCUMENT_ROOT'].'/config/database.php';
    preg_match('/(\d|\w)*(?=\.jpg)/', $img_src, $matches);
    $img_id = $matches[0];
    try {
        $DB = new PDO($DB_DSN.";dbname=".$DB_NAME, $DB_USER, $DB_PASSWORD);
        $stmt = $DB->prepare("INSERT INTO comments_info (img_id, acc_id, `user`, comments) VALUES (?, ?, ?, ?)");
        $stmt->execute([$img_id, $acc_id, $user, $value]);
        try {
            $stmt = $DB->prepare("SELECT comments FROM img_info WHERE img_id=?");
            $stmt->execute([$img_id]);
            $fetch = $stmt->fetch();
            if (!empty($img_id)) {
                try {
                    $stmt = $DB->prepare("UPDATE img_info SET comments=? WHERE img_id=?");
                    $stmt->execute([intval($fetch['comments']) + 1, $img_id]);
                }  catch (PDOException $e) {
                    exit ;
                }
                try {
                    $stmt = $DB->prepare("SELECT acc_id, title FROM `img_info` WHERE img_id=?");
                    $stmt->execute([$img_id]);
                    $id = $stmt->fetch();
                    $stmt = $DB->prepare("SELECT `email`, comments_notify FROM `user_info` WHERE acc_id=?");
                    $stmt->execute([$id['acc_id']]);
                    $fetch = $stmt->fetch();
                    if ($id['acc_id'] !== $acc_id && intval($fetch['comments_notify']) === 1) {
                        $from = "no-reply@camagru.com";
                        mail($fetch['email'], "$user comment your photo !",
                            "$user comment : $value on $id[title] ! Go check it out on Share !", "From: $from");
                    }
                } catch (PDOException $e) {
                    exit ;
                }
            } else {
                exit ;
            }
        }  catch (PDOException $e) {
            exit ;
        }
    } catch (PDOException $e) {
        exit ;
    }
}

// Fetch comments from Database.

function    fetch_comments() {
    include $_SERVER['DOCUMENT_ROOT'].'/config/database.php';
    try {
        $DB = new PDO($DB_DSN.";dbname=".$DB_NAME, $DB_USER, $DB_PASSWORD);
        $stmt = $DB->prepare("SELECT * FROM comments_info");
        $stmt->execute([]);
        $comments = $stmt->fetchAll();
        echo json_encode($comments);
    } catch (PDOException $e) {
        echo $e;
    }
}

// Update and fetch like information from Database.

function    update_photos_likes($acc_id, $user, $img_id) {
    include $_SERVER['DOCUMENT_ROOT'].'/config/database.php';
    try {
        $DB = new PDO($DB_DSN.";dbname=".$DB_NAME, $DB_USER, $DB_PASSWORD);
        $stmt = $DB->prepare("SELECT `likes` FROM `img_info` WHERE img_id=?");
        $stmt->execute([$img_id]);
        $fetch = $stmt->fetch();
        $count = intval($fetch['likes']);
        try {
            $stmt = $DB->prepare("SELECT * FROM heart_info WHERE `img_id`=? AND `acc_id`=?");
            $stmt->execute([$img_id, $acc_id]);
            $likes = $stmt->fetch();
            if (empty($likes)) {
                try {
                    $stmt = $DB->prepare("INSERT INTO heart_info (img_id, acc_id) VALUES (?, ?)");
                    $stmt->execute([$img_id, $acc_id]);
                    try {
                        $stmt = $DB->prepare("UPDATE `img_info` SET `likes`=? WHERE img_id=?");
                        $stmt->execute([$count + 1, $img_id]);
                        try {
                            $stmt = $DB->prepare("SELECT acc_id, title FROM `img_info` WHERE img_id=?");
                            $stmt->execute([$img_id]);
                            $id = $stmt->fetch();
                            $stmt = $DB->prepare("SELECT `email`, likes_notify FROM `user_info` WHERE acc_id=?");
                            $stmt->execute([$id['acc_id']]);
                            $fetch = $stmt->fetch();
                            if ($id['acc_id'] !== $acc_id && intval($fetch['likes_notify']) === 1) {
                                $from = "no-reply@camagru.com";
                                mail($fetch['email'], "$user liked your photo !",
                                    "$user liked $id[title]", "From: $from");
                            }
                        } catch (PDOException $e) {
                            exit ;
                        }
                    } catch (PDOException $e) {
                        echo $e;
                    }
                    echo "1";
                    exit ;
                } catch (PDOException $e) {
                    echo $e;
                }
            } else if (!empty($likes) && $acc_id === $likes['acc_id']){
                try {
                    $stmt = $DB->prepare("DELETE FROM `heart_info` WHERE img_id=?");
                    $stmt->execute([$img_id]);
                    try {
                        $stmt = $DB->prepare("UPDATE `img_info` SET `likes`=? WHERE img_id=?");
                        $stmt->execute([$count - 1, $img_id]);
                    } catch (PDOException $e) {
                        echo $e;
                    }
                    echo "0";
                    exit ;
                } catch (PDOException $e) {
                    echo $e;
                }
            }
        } catch (PDOException $e) {
            echo $e;
        }
    } catch (PDOException $e) {
        throw $e;
    }
}

// Fetch counter information from Database to update profile page.

function    update_profile_counter($user) {
    include $_SERVER['DOCUMENT_ROOT'].'/config/database.php';
    $tab = array();
    $likes = 0;
    try {
        $DB = new PDO($DB_DSN.";dbname=".$DB_NAME, $DB_USER, $DB_PASSWORD);
        $stmt = $DB->prepare("SELECT * FROM img_info WHERE `user`=?");
        $stmt->execute([$user]);
        $img = $stmt->fetchAll();
        $stmt = $DB->prepare("SELECT * FROM comments_info WHERE `user`=?");
        $stmt->execute([$user]);
        $comments = count($stmt->fetchAll());
        if (!empty($img)) {
            foreach ($img as $arr) {
                foreach ($arr as $key => $value)
                if ($key === 'likes') {
                    $likes += intval($value);
                }
            }
        }
        $tab[0] = count($img);
        $tab[1] = $comments;
        $tab[2] = $likes;
        echo json_encode($tab);
    } catch (PDOException $e) {
        exit ;
    }
}

// Fetch counter information from Database to update index page.

function    update_counter() {
    include $_SERVER['DOCUMENT_ROOT'].'/config/database.php';
    $tab = array();
    try {
        $DB = new PDO($DB_DSN.";dbname=".$DB_NAME, $DB_USER, $DB_PASSWORD);
        $stmt = $DB->prepare("SELECT * FROM img_info");
        $stmt->execute();
        $img = count($stmt->fetchAll());
        $stmt = $DB->prepare("SELECT * FROM user_info");
        $stmt->execute();
        $user = count($stmt->fetchAll());
        $stmt = $DB->prepare("SELECT * FROM comments_info");
        $stmt->execute();
        $comments = count($stmt->fetchAll());
        $tab[0] = $img;
        $tab[1] = $comments;
        $tab[2] = $user;
        echo json_encode($tab);
    } catch (PDOException $e) {
        exit ;
    }
}

// Fetch pictures information from Database to update profile page or old picture.

function    update_profile($user, $acc_id) {
    include $_SERVER['DOCUMENT_ROOT'].'/config/database.php';
    try {
        $DB = new PDO($DB_DSN.";dbname=".$DB_NAME, $DB_USER, $DB_PASSWORD);
        $stmt = $DB->prepare("SELECT * FROM img_info WHERE `user`=?");
        $stmt->execute([$user]);
        $log = $stmt->fetchAll();
        try {
            $stmt = $DB->prepare("SELECT * FROM heart_info WHERE acc_id=?");
            $stmt->execute([$acc_id]);
            $likes = $stmt->fetchAll();
            $count = count($likes);
            foreach ($log as $key => $value) {
                $i = 0;
                while ($i < $count) {
                    if ($likes[$i]['acc_id'] === $acc_id) {
                        for ($j = 0; $j < count($likes); $j++) {
                            if ($likes[$j]['img_id'] === $value['img_id']) {
                                $log[$key]['liked'] = "isLiked";
                                $i = $count;
                                break ;
                            }
                        }
                    } else {
                        $log[$key]['liked'] = "";
                    }
                    $i++;
                }
            }
            echo json_encode($log);
        } catch (PDOException $e) {
            exit ;
        }
    } catch (PDOException $e) {
       throw $e;
    }
}

// Fetch pictures information from Database to update index page.

function    update_index($acc_id, $scroll) {
    include $_SERVER['DOCUMENT_ROOT'].'/config/database.php';
    try {
        $DB = new PDO($DB_DSN.";dbname=".$DB_NAME, $DB_USER, $DB_PASSWORD);
        $stmt = $DB->prepare("SELECT * FROM img_info ORDER BY `creation_time` DESC LIMIT ?, 4");
        $stmt->bindParam(1, $scroll, PDO::PARAM_INT);
        $stmt->execute();
        $log = $stmt->fetchAll();
//        print_r ($log);
        try {
            $stmt = $DB->prepare("SELECT * FROM heart_info WHERE acc_id=?");
            $stmt->execute([$acc_id]);
            $likes = $stmt->fetchAll();
            $count = count($likes);
            foreach ($log as $key => $value) {
                $i = 0;
                while ($i < $count) {
                    if ($likes[$i]['acc_id'] === $acc_id) {
                        for ($j = 0; $j < count($likes); $j++) {
                            if ($likes[$j]['img_id'] === $value['img_id']) {
                                $log[$key]['liked'] = "isLiked";
                                $i = $count;
                                break ;
                            }
                        }
                    } else {
                        $log[$key]['liked'] = "";
                    }
                    $i++;
                }
            }
            echo json_encode($log);
        } catch (PDOException $e) {
           exit ;
        }
    } catch (PDOException $e) {
        exit ;
    }
}

// Erase a picture and pictures info.

function    erase_picture($img_src, $acc_id) {
    include $_SERVER['DOCUMENT_ROOT'].'/config/database.php';
    preg_match('/(\d|\w)*(?=\.jpg)/', $img_src, $img_id);
    try {
        $DB = new PDO($DB_DSN.";dbname=".$DB_NAME, $DB_USER, $DB_PASSWORD);
        $stmt = $DB->prepare("SELECT `id`, acc_id FROM img_info WHERE img_id=?");
        $stmt->execute([$img_id[0]]);
        $log = $stmt->fetch();
        if ($acc_id !== $log['acc_id']) {
            exit ;
        } else if (!empty($log)) {
            try {
                $stmt = $DB->prepare("DELETE FROM `img_info` WHERE img_id=?");
                $stmt->execute([$img_id[0]]);
                unlink("../pictures/$img_id[0].jpg");
                try {
                    $stmt = $DB->prepare("DELETE FROM `comments_info` WHERE img_id=?");
                    $stmt->execute([$img_id[0]]);
                    echo json_encode($log);
                } catch (PDOException $e) {
                    exit ;
                }
            } catch (PDOException $e) {
                exit ;
            }
        }
    } catch (PDOException $e) {
        header("Location: ../sign_up.php?error=Database error :(");
    }
}