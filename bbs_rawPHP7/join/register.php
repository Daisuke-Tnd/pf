<?php
session_start();
require('../dbconnect.php');

if (!empty($_POST)) {
	if ($_POST['name'] === '') {
		$error['name'] = 'blank';
	}
	if ($_POST['email'] === '') {
		$error['email'] = 'blank';
	}
	if (strlen($_POST['password']) < 4) {
		$error['password'] = 'length';
	}
	if ($_POST['password'] === '') {
		$error['password'] = 'blank';
	}
	//アカウントの重複を確認
	if (empty($error)) {
		$member = $dbh->prepare('SELECT * FROM members WHERE email = ? OR name = ?');
		$member->execute(array($_POST['email'], $_POST['name']));
		$record = $member->fetch();
		if ($record['email'] == $_POST['email']) {
			$error['email'] = 'duplicate';
		}
		if ($record['name'] == $_POST['name']) {
			$error['name'] = 'duplicate';
		}
	}
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
				sha1(uniqid(mt_rand(), true)), // 乱数を生成(randよりも動作が早いとのことで、mt_randを採用
				$ext
			);
			move_uploaded_file($_FILES['image']['tmp_name'], '../member_picture/' . $image);
		}
		$_SESSION['join'] = $_POST;
		$_SESSION['join']['image'] = $image; // sessionに画像の名前を保存
		header('Location: check.php');
		exit();
	}
}
if (!empty($_REQUEST['action']) && $_REQUEST['action'] == 'rewrite' && !empty($_SESSION['join'])) {
	$_POST = $_SESSION['join'];
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>会員登録</title>
<link rel="stylesheet" href="../style.css" />
</head>
<body>
<div id="wrap">
<div id="head">
<h1>会員登録</h1>
</div>
<div id="content">
<p>次のフォームに必要事項をご記入ください。</p>
<form action="" method="post" enctype="multipart/form-data">
<dl>
<dt>名前<span class="required">必須</span></dt>
<dd>
<input type="text" name="name" size="35" maxlength="255" value="
<?php if (!empty($_POST['name'])) : ?>
<?php print(htmlspecialchars($_POST['name'])); ?>
<?php endif; ?>" />
<?php if (!empty($error['name']) && $error['name'] === 'blank') : ?>
<p class='error'>*名前を入力してください。</p>
<?php elseif (!empty($error['name']) && $error['name'] === 'duplicate') : ?>
<p class='error'>*指定された名前は既に登録されています。</p>
<?php endif; ?>
</dd>
<dt>メールアドレス<span class="required">必須</span></dt>
<dd>
<input type="text" name="email" size="35" maxlength="255" value="
<?php if (!empty($_POST['email'])) : ?>
<?php print(htmlspecialchars($_POST['email'])); ?>
<?php endif; ?>" />
<?php if (!empty($error['email']) && $error['email'] === 'bank') : ?>
<p class='error'>*メールアドレスを入力してください。</p>
<?php elseif (!empty($error['email']) && $error['email'] === 'duplicate') : ?>
<p class='error'>*指定されたメールアドレスは既に登録されています。</p>
<?php endif; ?>
<dt>パスワード<span class="required">必須</span></dt>
<dd>
<input type="password" name="password" size="10" maxlength="20" value="" />
<?php if (!empty($error['password']) && $error['password'] === 'length') : ?>
<p class='error'>*4文字以上で入力してください。</p>
<?php elseif (!empty($error['password']) && $error['password'] === 'blank') : ?>
<p class='error'>*パスワードを入力してください。</p>
<?php endif; ?>
</dd>
<dt>写真など</dt>
<dd>
<!--MAX_FILE_SIZE でファイルサイズを制限する-->
<input type="hidden" name="MAX_FILE_SIZE" value="200000">
<input type="file" name="image" size="35" value="test"  />
<?php if (!empty($error['image']) && $error['image'] === 'type') : ?>
<p class="error">*写真などは「.gif」または「.jpg」「.png」の画像を指定してください</p>
<?php endif; ?>
</dd>
</dl>
<div><input type="submit" value="入力内容を確認する" /></div>
<?php if (!empty($_FILES['image']['error']) && $_FILES['image']['error'] == 2) : ?>
<p class="error">画像のサイズは200KB以内にしてください</p>
<?php endif; ?>
<p>ログインはこちらへ。</p>
<p>&raquo;<a href="../login.php">ログインをする</a></p>
<p>掲示板はこちらへ。</p>
<p>&raquo;<a href="../index.php">会員登録をせずに掲示板をみる</a></p>
</form>
</div>
</body>
</html>
