<?php
session_start();
require('dbconnect.php');

if (!empty($_SESSION['id'] && $_SESSION['time'] + 3600 > time())) {
	$_SESSION['time'] = time();
	$members = $dbh->prepare('SELECT * FROM members WHERE id = ?');
	$members->execute(array($_SESSION['id']));
	$member = $members->fetch();
} else {
	header('Location: join/register.php');
	exit();
}

if (!empty($_POST) && $_POST['message'] !== '') {
	$message = $dbh->prepare('INSERT INTO posts SET member_id = ?, title = ?, message = ?, created = NOW()');
	$message->execute(array(
		$member['id'],
		$_POST['title'],
		$_POST['message'],
	));
	header('Location: index.php');
	exit();
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>投稿</title>
<link rel="stylesheet" href="style.css" />
</head>
<body>
<div id="wrap">
<div id="head">
<h1>投稿する</h1>
</div>
<div id="content">
<form action="" method="post">
<dt>タイトル</dt>
<dd>
<input type=text name="title" size=50>
<input type="hidden" name="reply_post_id" value="<?php print(htmlspecialchars($_REQUEST['res'], ENT_QUOTES)); ?>"/>
</dd>
</dl>
<dl>
<dt>本文</dt>
<dd>
<textarea name="message" cols="50" rows="5"></textarea>
<input type="hidden" name="reply_post_id" value="<?php print(htmlspecialchars($_REQUEST['res'], ENT_QUOTES)); ?>"/>
</dd>
</dl>
<div>
<p>
<input type="submit" value="投稿する" />
</p>
</div>
</form>
</div>
</body>
</html>
