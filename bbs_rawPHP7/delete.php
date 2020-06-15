<?php
session_start();
require('dbconnect.php');

if (!empty($_GET['id'] && $_SESSION['id'] && $_SESSION['time'] + 3600 > time())) {
	$_SESSION['time'] = time();
	$posts = $dbh->prepare('SELECT * FROM posts WHERE id = ?');
	$posts->execute(array($_GET['id']));
	$post = $posts->fetch();
} else {
	header('Location: join/register.php');
	exit();
}
if (!empty($_SESSION['id'])) {
	$id = $_REQUEST['id'];
	$messages = $dbh->prepare('SELECT * FROM posts WHERE id = ?');
	$messages->execute(array($id));
	$message = $messages->fetch();
	if ($message['member_id'] == $_SESSION['id']) {
		$del = $dbh->prepare('UPDATE posts SET is_deleted = 1 WHERE id = ?');
		$del->execute(array($id));
	}
}
header('Location: index.php');
exit();
