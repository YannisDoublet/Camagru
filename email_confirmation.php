<?php
header("refresh: 6; url=sign_up.php");
?>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Camagru</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="style/email_confirmation_style.css" />
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
        <p class="success_title">Account successfully created !</p>
        <hr style="width: 560px; background-color:black; height: 1px;">
        <p class="success_description">Thank you for signing up, you will be redirect to login page soon ! An email has been send to you, please confirm your account.</p>
    </main>
    <footer class="footer">
        <div class="copyright">&copy; 2019 Yannis Doublet</div>
        <div class="signature">SHARE</div>
    </footer>
</div>
</body>
</html>