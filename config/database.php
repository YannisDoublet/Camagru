<?php
	$DB_DSN = 'mysql:host=127.0.0.1:3306';
	$DB_USER = 'root';
	$DB_PASSWORD = 'root';
	$DB_NAME = 'Camagru';
	$DB_USER_INFO = 'user_info';
	$DB_IMG_INFO = 'img_info';
	$DB_HEART_INFO = 'heart_info';
	$DB_COMMENTS_INFO = 'comments_info';
	$DB_USER_INFO_CONTENT = "CREATE TABLE IF NOT EXISTS $DB_USER_INFO (
			`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
			`firstname` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
			`lastname` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
			`email` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
			`username` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
			`password` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
			`photo_uploaded` INT ,
			`comment_uploaded` INT ,
			`acc_id` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
			`validate` INT ,
			`comments_notify` INT,
			`likes_notify` INT,
			`creation_time` DATETIME NOT NULL
			);";

$DB_IMG_INFO_CONTENT = "CREATE TABLE IF NOT EXISTS $DB_IMG_INFO (
			`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
			`img_id` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
			`acc_id` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
			`title` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
			`user` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
			`firstname` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
			`comments` INT ,
			`likes` INT ,
			`creation_time` DATETIME NOT NULL
			);";

$DB_HEART_INFO_CONTENT = "CREATE TABLE IF NOT EXISTS $DB_HEART_INFO (
			`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
			`img_id` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
			`acc_id` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
			);";

$DB_COMMENTS_INFO_CONTENT = "CREATE TABLE IF NOT EXISTS $DB_COMMENTS_INFO (
			`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
			`img_id` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
			`acc_id` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
			`user` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
			`comments` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
			);";