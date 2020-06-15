<?php
session_start();
require('dbconnect.php');

if (!empty($_SESSION['time'] + 3600 > time())) {
	$_SESSION['time'] = time();
	$posts = $dbh->prepare('SELECT * FROM posts WHERE id = ?');
	$posts->execute(array($_GET['id']));
	$post = $posts->fetch();
	if ($_SESSION['id'] !== $post['member_id']) {
		header('Location: index.php');
		exit();
	}
} else {
	header('Location: join/register.php');
	exit();
}
if (!empty($_POST) && $_POST['message'] !== '') {
	$message = $dbh->prepare('UPDATE posts SET title = ?, message = ? WHERE id = ?');
	$message->execute(array(
		$_POST['title'],
		$_POST['message'],
		$_POST['id']
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
<title>編集</title>
<link rel="stylesheet" href="style.css" />
</head>
<body>
<div id="wrap">
<div id="head">
<h1>編集する</h1>
</div>
<div id="content">
<form action="" method="post">
<dl>
<dt>タイトル</dt>
<dd>
<input type="text" name="title" size=50 value="<?php print(htmlspecialchars($post['title'], ENT_QUOTES)); ?>"/>
</dd>
</dl>
<dl>
<dt>本文</dt>
<dd>
<textarea name="message" cols="50" rows="5"><?php print(htmlspecialchars($post['message'], ENT_QUOTES)); ?></textarea>
</dd>
</dl>
<dl>
<dd>
<input type="hidden" name="id" value="<?php print(htmlspecialchars($post['id'], ENT_QUOTES)); ?>"/>
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
