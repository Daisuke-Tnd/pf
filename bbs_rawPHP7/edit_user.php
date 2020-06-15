<?php
session_start();
require('dbconnect.php');

if (!empty($_SESSION['time'] + 3600 > time())) {
	$_SESSION['time'] = time();
	$posts = $dbh->prepare('SELECT * FROM members WHERE id = ?');
	$posts->execute(array($_SESSION['id']));
	$post = $posts->fetch();
	if ($_SESSION['id'] !== $post['id']) {
		header('Location: index.php');
		exit();
	}
} else {
	header('Location: join/register.php');
	exit();
}
if (!empty($_POST)) {
	if (!empty($_FILES['image']['tmp_name'])) {
		function validateImageType() {
			$type = exif_imagetype($_FILES['image']['tmp_name']);
			switch($type) {
			case IMAGETYPE_GIF:
				return 'gif';
			case IMAGETYPE_JPEG:
				return 'jpg';
			case IMAGETYPE_PNG:
				return 'png';
			default:
				return 'type';
			}
		}
		$ext = validateImageType();
	}
	if (!empty($ext) && $ext === 'type') {
		$error['image'] = 'type';
	} elseif (empty($error) && $_FILES['image']['error'] !== 2) {
		// 画像アップロードあり
		if (!empty($_FILES['image']['tmp_name'])) {
			$image = sprintf(
				'%s_%s.%s',
				time(),
				sha1(uniqid(mt_rand(), true)), // 乱数を生成(randよりも動作が早いとのことで、mt_randを採用)
				$ext
			);
			move_uploaded_file($_FILES['image']['tmp_name'], 'member_picture/' . $image);
		}
		$_SESSION['join'] = $_POST;
		$_SESSION['join']['image'] = $image;
		$message = $dbh->prepare('UPDATE members SET comment = ?, user_file = ? WHERE id = ?');
		$message->execute(array(
			$_SESSION['join']['comment'],
			$_SESSION['join']['image'],
			$post['id']
		));
		header('location: index.php');
		exit();
	}
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>ユーザー情報編集画面</title>
<link rel="stylesheet" href="style.css" />
</head>
<body>
<div id="wrap">
<div id="head">
<h1>ユーザー情報の編集</h1>
</div>
<div id="content">
<form action="" method="post" enctype="multipart/form-data">
<dl>
<dt>一言コメント</dt>
<dd>
<input type="text" name="comment" size=50 value="
<?php if (!empty($post['comment'])) : ?>
<?php print(htmlspecialchars($post['comment'], ENT_QUOTES)); ?>
<?php endif; ?>"/>
</dd>
<dt>写真など</dt>
<dd>
<!--MAX_FILE_SIZE でファイルサイズを制限する-->
<input type="hidden" name="MAX_FILE_SIZE" value="200000">
<input type="file" name="image" size="35" value="
<?php if (!empty($post['user_file'])) : ?>
<?php print(htmlspecialchars($post['user_file'], ENT_QUOTES)); ?>
<?php endif; ?>"/>
<?php if (!empty($error['image']) && $error['image'] === 'type') : ?>
<p class="error">*写真などは「.gif」または「.jpg」「.png」の画像を指定してください</p>
<?php endif; ?>
<?php if (!empty($_FILES['image']['error']) && $_FILES['image']['error'] == 2) : ?>
<p class="error">画像のサイズは200KB以内にしてください</p>
<?php endif; ?>
</dd>
</dl>
<div><input type="submit" value="更新する" /></div>
<p>掲示板はこちらへ。</p>
<p>&raquo;<a href="index.php">掲示板へ戻る</a></p>
</form>
</div>
</body>
</html>
