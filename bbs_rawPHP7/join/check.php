<?php
session_start();
require('../dbconnect.php');

if (!isset($_SESSION['join'])) {
	header('Location: index.php');
	exit();
}
if (!empty($_POST)) {
	$stmt = $dbh->prepare('INSERT INTO members SET name = :name, email = :email, password = :password, user_file = :user_file');
	$stmt->execute(array(
		':name' => $_SESSION['join']['name'],
		':email' => $_SESSION['join']['email'],
		':password' => $_SESSION['join']['password'],
		':user_file' => $_SESSION['join']['image']
	));
	unset($_SESSION['join']);
	header('Location: thanks.php');
	exit();
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>会員登録</title>
<link rel="stylesheet" href="../style.css" />
</head>
<body>
<div id="wrap">
<div id="head">
<h1>会員登録</h1>
</div>
<div id="content">
<p>記入した内容を確認して、「登録する」ボタンをクリックしてください</p>
<form action="" method="post">
<input type="hidden" name="action" value="submit" />
<dl>
<dt>名前</dt>
<dd>
<?php echo(htmlspecialchars($_SESSION['join']['name'], ENT_QUOTES)); ?>
</dd>
<dt>メールアドレス</dt>
<dd>
<?php echo(htmlspecialchars($_SESSION['join']['email'], ENT_QUOTES)); ?>
</dd>
<dt>パスワード</dt>
<dd>
【表示されません】
</dd>
<dt>写真など</dt>
<dd>
<?php if (!empty($_SESSION['join']['image'])) : ?>
<img src="../member_picture/<?php print(htmlspecialchars($_SESSION['join']['image'], ENT_QUOTES)); ?>">
<?php else : ?>
<p>未登録</p>
<?php endif; ?>
</dd>
<div><a href="register.php?action=rewrite">&laquo;&nbsp;書き直す</a> | <input type="submit" value="登録する" /></div>
</form>
</div>
</div>
</body>
</html>
