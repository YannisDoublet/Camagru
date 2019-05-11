<?php
session_start();
if (!file_exists("users")) {
    mkdir("users", 0700);
}
if (file_exists("assets/stickers")) {
    $files = array_diff(scandir('assets/stickers'), array('.', '..'));
}
$i = 0;
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Share</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" media="screen" href="style/mainpage_style.css" />
    <link rel="stylesheet" type="text/css" media="screen" href="style/post_style.css" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    <link rel="icon" href="assets/photography-icon-png-2382.png" type="image/x-icon">
    <script async src="front/mainpage.js"></script>
    <script async src="front/update_mainpage.js"></script>
    <script async src="front/post_photos.js"></script>
    <script async src="front/photo_options.js"></script>
</head>
<body>
<div id="post_picture" class="post_picture hidden">
    <div class="post_container">
        <div class="post_card">
            <div id="post_card_content">
                <div class="post_card_header">
                    <i class="fas fa-arrow-left" onclick="togglePost()"></i>
                    <p class="post_card_header_title">Post a picture</p>
                </div>
                <div class="post_card_picture">
                    <div id="webcam" class="webcam_option" onclick="toggleWebcam()">
                        <video id="video" class="" autoplay></video>
                    </div>
                    <div id="upload" class="upload_option">
                        <label id="upload_label" for="file_profile" class="label_upload"></label>
                        <input id="file_profile" style="display: none;" type="file" name="picture" accept="image/*" onchange="upload_img(event)">
                    </div>
                    <img id="selected" style=" position: absolute; top: 0px; left: 0px">
                    <i id="move_left" class="fas fa-arrow-left move_sticker none" onmousedown="mousedownfunc(event)" onmouseup="mouseupfunc(event)" onmouseleave="mouseupfunc(event)"></i>
                    <i id="move_up" class="fas fa-arrow-up move_sticker none" onmousedown="mousedownfunc(event)" onmouseup="mouseupfunc(event)" onmouseleave="mouseupfunc(event)"></i>
                    <i id="move_right" class="fas fa-arrow-right move_sticker none" onmousedown="mousedownfunc(event)" onmouseup="mouseupfunc(event)" onmouseleave="mouseupfunc(event)"></i>
                    <i id="move_down" class="fas fa-arrow-down move_sticker none" onmousedown="mousedownfunc(event)" onmouseup="mouseupfunc(event)" onmouseleave="mouseupfunc(event)"></i>
                    <img id="picture" src="" class="none">
                    <canvas id="canvas" style="display: none;"></canvas>
                </div>
                <div id="utils" class="post_card_utils none">
                    <button disabled id="take_photo" class="" onclick="photo()">Take a photo</button>
                    <input id="title" class="none" type="text" placeholder="Name your picture !" required>
                    <button id="post" class="none" onclick="submit()">Post</button>
                </div>
                <div id="stickers_bar" class="post_card_bar none">
                    <p class="post_card_bar_header" onclick="toggleStickers()">Stickers</p>
                    <div id="stickers">
                        <div id="stickers_container">
                            <?php foreach($files as $value) {
                                ?><img id="<?= $i?>" class="stickers_img" src="assets/stickers/<?= $value?>" onclick="toggleActive(event)">
                            <?php $i++; }?>
                        </div>
                    </div>
                </div>
                <div id="old_pic" class="post_card_bar none">
                    <p class="post_card_bar_header" onclick="toggleOld()">Previous picture</p>
                    <div id="old"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="grid-container" class="grid-container">
    <header class="header">
        <i class="fas fa-bars" onclick="Sidebar()"></i>
        <form action="#" method="POST">
            <input class="searchbar" type="text" placeholder="Search...">
        </form>
    </header>
    <aside id="sidenavbar" class="sidenavbar">
        <div class="sidenavbar_content">
            <h1>SHARE</h1>
            <ul class="sidenavbar_box">
                <li class="sidenavbar_box-content"><a class="sidenavbar_links" <?php if (!empty($_SESSION['logued_user'])) { echo 'onclick="togglePost();"'; } else { echo 'href="sign_up.php?error=You must be logged in!"'; }?>>Post a Picture</a></li>
                <button id="sidenavbar_button" class="sidenavbar_button">Account
                    <i class="fa fa-caret-down"></i>
                </button>
                <div class="sidenavbar_account_content">
                    <?php if (empty($_SESSION['logued_user'])){?>
                        <li class="sidenavbar_account-box-content none"><a class="sidenavbar_links" href="sign_up.php" >Sign Up</a></li>
                    <?php }else{?>
                        <li class="sidenavbar_account-box-content none"><a class="sidenavbar_links" href="profile.php">My Profile</a></li>
                        <li class="sidenavbar_account-box-content none"><a class="sidenavbar_links" href="back/logout.php">Sign Out</a></li>
                    <?php }?>
                </div>
            </ul>
        </div>
    </aside>
    <main class="content">
        <div style="background: url('<?php if (!empty($_SESSION['username'])) {
            ?>users/<?= $_SESSION['username']?>/banner.jpg<?php }
            else { echo "assets/mountains.png"; }?>') no-repeat center; background-size: cover;" class="content_banner">
            <?php if (!empty($_SESSION['logued_user'])) {?>
                <div class="content_banner-user"><p style="margin: 0; padding: 2px" class="banner_total_desc">Hello <?= $_SESSION['logued_user'];?>, how are you today ?</p></div>
            <?php } else {?>
                <div class="content_banner-user"><p style="margin: 0; padding: 2px" class="banner_total_desc">Welcome to Share ! The new social network based on photography !</p></div>
            <?php }?>
            <div class="content_banner-overview">
                <div class="banner-overview_content">
                    <div class="total_content"></div>
                    <div class="banner_total_desc">Photos Uploaded on Share</div>
                </div>
                <div class="banner-overview_content">
                    <div class="total_content"></div>
                    <div class="banner_total_desc">Comments Written on Share</div>
                </div>
                <div class="banner-overview_content">
                    <div class="total_content"></div>
                    <div class="banner_total_desc">Users Registered on Share</div>
                </div>
            </div>
        </div>
            <div id="content_card" class="content_card">
                <p id="message" style="color: black; font-size: 40px; align-items: center">Be the first to post on Share !</p>
            </div>
    </main>
    <footer class="footer">
        <div class="copyright">&copy; 2019 Yannis Doublet</div>
        <div class="signature">Camagru, a 42 Project</div>
    </footer>
</div>
</body>
</html>