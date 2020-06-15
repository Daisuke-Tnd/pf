<?php
session_start();
require('dbconnect.php');

if (!empty($_COOKIE['email']) && $_COOKIE['email'] !== '') {
	$email = $_COOKIE['email'];
}
if (!empty($_POST)) {
	$email = $_POST['email'];
	if ($_POST['email'] !== '' && $_POST['password'] !== '') {
		$login = $dbh->prepare('SELECT * FROM members WHERE email = ? AND password = ?');
		$login->execute(array(
			$_POST['email'],
			$_POST['password']
		));
		$member = $login->fetch();
		if ($member) {
			$_SESSION['id'] = $member['id'];
			$_SESSION['time'] = time();
			if ($_POST['save'] === 'on') {
				setcookie('email', $_POST['email'], time() + 60 * 60 * 24 * 14);
			}
			header('Location: index.php');
			exit();
		} else {
			$error['login'] = 'failed';
		}
	} else {
		$error['login'] = 'blank';
	}
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<link rel="stylesheet" type="text/css" href="style.css" />
<title>ログインする</title>
</head>
<body>
<div id="wrap">
<div id="head">
<h1>ログインする</h1>
</div>
<div id="content">
<div id="lead">
<p>メールアドレスとパスワードを記入してログインしてください。</p>
</div>
<form action="" method="post">
<dl>
<dt>メールアドレス</span></dt>
<dd>
<input type="text" name="email" size="35" maxlength="255" value="
<?php if (!empty($email)) : ?>
<?php print(htmlspecialchars($email, ENT_QUOTES)); ?>
<?php endif; ?>" />
<?php if (!empty($error['login']) && $error['login'] === 'blank') : ?>
<p class='error'>*メールアドレスとパスワードを入力してください。</p>
<?php elseif (!empty($error['login']) && $error['login'] === 'failed') : ?>
<p class='error'>*ログインに失敗しました。正しく入力してください。</p>
<?php endif; ?>
<dt>パスワード</dt>
<dd>
<input type="password" name="password" size="10" maxlength="20" value="" />
<dt>ログイン情報の記録</dt>
<dd>
<input id="save" type="checkbox" name="save" value="on">
<label for="save">次回からは自動的にログインする</label>
</dd>
</dl>
<div>
<input type="submit" value="ログインする" />
</div>
</form>
<p>会員登録はこちらへ。</p>
<p>&raquo;<a href="join/register.php">会員登録をする</a></p>
<p>掲示板はこちらへ。</p>
<p>&raquo;<a href="index.php">ログインをせずに掲示板をみる</a></p>
<p>パスワードを忘れた方はこちらへ。</p>
<p>&raquo;<a href="reset.php">パスワードの再発行をする</a></p>
</div>
</div>
</body>
</html>
