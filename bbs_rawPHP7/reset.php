<?php
session_start();
require('dbconnect.php');

function setToken() {
	$token = sha1(uniqid(mt_rand(), true));
	return $token;
}
$token = setToken();
$exp = date("Y/m/d H:i:s", strtotime("+30 minute")); // expiration date = 30min
function mailSend($mail) {
	global $token;
	mb_language('Japanese');
	mb_internal_encoding('UTF-8');
	$to = $mail;
	$subject = "パスワード再発行";
	$message = "30分間のみ有効なURLです\nhttps://procir-study.site/tsunoda277/bbs/reset_pass.php?token=" . $token;
	$headers = "From: example@procir-study.site";
	$pfrom = "-f info@test.com";
	mb_send_mail($to, $subject, $message, $headers, $pfrom);
}
if (!empty($_POST['email']) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
	$member = $dbh->prepare('SELECT * FROM members WHERE email = ?');
	$member->execute(array($_POST['email']));
	$record = $member->fetch();
	if ($record['email'] == $_POST['email']) {
		mailSend($_POST['email']);
		$member = $dbh->prepare('UPDATE members SET token = ?, expiration = ? WHERE id = ?;');
		$member->execute(array($token, $exp, $record['id']));
	}
	$result = 'パスワード再発行用のURLを送信しました';
} elseif (!empty($_POST['email']) && !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
	$result = 'メールアドレスの形式が正しくありません。';
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>パスワード再発行</title>
<link rel="stylesheet" href="style.css" />
</head>
<body>
<div id="wrap">
<div id="head">
<h1>パスワード再発行</h1>
</div>
<div id="content">
<p>登録済みのメールアドレスをご記入ください。</p>
<form action="" method="post">
<dl>
<dt>メールアドレス</dt>
<dd>
<input type="text" name="email" size="35" maxlength="255" value="
<?php if (!empty($_POST['email'])) : ?>
<?php print(htmlspecialchars($_POST['email'])); ?>
<?php endif; ?>" />
</dl>
<input type="hidden" name="token" value="<?php echo $token; ?>">
<div><input type="submit" value="送信する" /></div>
<?php if (!empty($result)) : ?>
<span class="error">*<?php echo $result; ?></span>
<?php endif; ?>
<p>ログインはこちらへ。</p>
<p>&raquo<a href="login.php">ログインをする</a></p>
<p>会員登録はこちらへ。</p>
<p>&raquo<a href="join/register.php">会員登録をする</a></p>
</form>
</div>
</body>
</html>
