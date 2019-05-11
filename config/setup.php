<?php
include 'database.php';
try {
	$NEW = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
	echo "Connexion à MySQL réussi.";
	$req = "CREATE DATABASE IF NOT EXISTS $DB_NAME DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci";
	$NEW->prepare($req)->execute();
	echo "<br>Base de donnée ".$DB_NAME." créé !";
} catch (PDOException $e) {
	echo "La base de donnée n'à pas pu être reliée.";
}

try {
	$DB = new PDO($DB_DSN.";dbname=".$DB_NAME, $DB_USER, $DB_PASSWORD);
	echo "<br>Connexion à la base de donnée ".$DB_NAME." réussi !";
	$req = $DB_USER_INFO_CONTENT;
	$DB->prepare($req)->execute();
	echo "<br>Table ".$DB_USER_INFO." créé !";
} catch (PDOException $e) {
	echo "<br>La base de donnée n'à pas pu se connecter à Camagru.";
}

try {
    $DB = new PDO($DB_DSN.";dbname=".$DB_NAME, $DB_USER, $DB_PASSWORD);
    echo "<br>Connexion à la base de donnée ".$DB_NAME." réussi !";
    $req = $DB_IMG_INFO_CONTENT;
    $DB->prepare($req)->execute();
    echo "<br>Table ".$DB_IMG_INFO." créé !";
} catch (PDOException $e) {
    echo "<br>La base de donnée n'à pas pu se connecter à Camagru.";
}

try {
    $DB = new PDO($DB_DSN.";dbname=".$DB_NAME, $DB_USER, $DB_PASSWORD);
    echo "<br>Connexion à la base de donnée ".$DB_NAME." réussi !";
    $req = $DB_HEART_INFO_CONTENT;
    $DB->prepare($req)->execute();
    echo "<br>Table ".$DB_HEART_INFO." créé !";
} catch (PDOException $e) {
    echo "<br>La base de donnée n'à pas pu se connecter à Camagru.";
}

try {
    $DB = new PDO($DB_DSN.";dbname=".$DB_NAME, $DB_USER, $DB_PASSWORD);
    echo "<br>Connexion à la base de donnée ".$DB_NAME." réussi !";
    $req = $DB_COMMENTS_INFO_CONTENT;
    $DB->prepare($req)->execute();
    echo "<br>Table ".$DB_COMMENTS_INFO." créé !";
} catch (PDOException $e) {
    echo "<br>La base de donnée n'à pas pu se connecter à Camagru.";
}
