<?php
session_start();
require('dbconnect.php');

if (!empty($_POST['password']) && strlen($_POST['password']) < 4) {
	$error['password'] = 'length';
} elseif (empty($error) && !empty($_GET['token']) && !empty($_POST['password'])) {
	$stmt = $dbh->prepare('SELECT * FROM members WHERE token = ?');
	$stmt->execute(array($_GET['token']));
	$record = $stmt->fetch();
	// 現在時刻が有効期限より前の場合のみ更新
	if ($_GET['token'] == $record['token'] && strtotime($record['expiration']) > strtotime(date("Y/m/d H:i:s"))) {
		$members = $dbh->prepare('UPDATE members SET password = ?, token = ? WHERE token = ?');
		$members->execute(array(
			$_POST['password'],
			sha1(uniqid(mt_rand(), true)),
			$record['token']
		));
		$result = 'パスワードを更新しました';
	} else {
		header('Location: reset_error.php');
		exit;
	}
} elseif (!empty($_POST['password'])) {
	header('Location: reset_error.php');
	exit;
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>パスワードの変更</title>
<link rel="stylesheet" href="style.css" />
</head>
<body>
<div id="wrap">
<div id="head">
<h1>パスワードの変更</h1>
</div>
<div id="content">
<form action="" method="post">
<dl>
<dt>パスワード</dt>
<dd>
<input type="password" name="password" size="10" maxlength="20" value="" />
<?php if (!empty($error['password']) && $error['password'] === 'length') : ?>
<p class='error'>*4文字以上で入力してください。</p>
<?php elseif (!empty($error['password']) && $error['password'] === 'blank') : ?>
<p class='error'>*パスワードを入力してください。</p>
<?php endif; ?>
<?php if (!empty($result)) : ?>
<p class='error'>*<?php echo $result; ?></p>
<?php endif; ?>
</dd>
</dl>
<div><input type="submit" value="パスワードを更新する" /></div>
</form>
</div>
</div>
</div>
</body>
</html>
