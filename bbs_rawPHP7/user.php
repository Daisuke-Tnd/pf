<?php
session_start();
require('dbconnect.php');

$posts = $dbh->prepare('SELECT m.name, m.email, m.user_file, m.comment, p.* FROM members m, posts p WHERE m.id = p.member_id AND is_deleted = 0 AND p.id = ?');
$posts->execute(array($_GET['id']));
$post = $posts->fetch();
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>ユーザー情報</title>
<link rel="stylesheet" href="style.css" />
</head>
<body>
<div id="wrap">
<div id="head">
<h1>ユーザー情報</h1>
</div>
<div id="content">
<form action="" method="post">
<?php if (!empty($_SESSION['id']) && $_SESSION['id'] == $post['member_id']) : ?>
<p>登録内容の変更はこちらへ。</p>
<p>&raquo;
<a href="edit_user.php">登録内容の変更</a>
<span>（※一言コメント、ユーザー画像のみ）</span>
</p>
<?php endif; ?>
<dl>
<dt>名前</dt>
<dd>
<?php echo(htmlspecialchars($post['name'], ENT_QUOTES)); ?>
</dd>
<dt>メールアドレス</dt>
<dd>
<?php echo(htmlspecialchars($post['email'], ENT_QUOTES)); ?>
</dd>
<dt>一言コメント</dt>
<dd>
<?php if (!empty($post['comment'])) : ?>
<?php echo(htmlspecialchars($post['comment'], ENT_QUOTES)); ?>
<?php else : ?>
<p>未登録</p>
<?php endif; ?>
</dd>
<dt>ユーザー画像</dt>
<dd>
<?php if (!empty($post['user_file'])) : ?>
<img src="member_picture/<?php print(htmlspecialchars($post['user_file'], ENT_QUOTES)); ?>" width="100" height="100"/>
<?php else : ?>
<p>未登録</p>
<?php endif; ?>
</dd>
</dl>
<p>掲示板はこちらへ。</p>
<p>&raquo;<a href="index.php">掲示板へ戻る</a></p>
</form>
</div>
</body>
</html>
