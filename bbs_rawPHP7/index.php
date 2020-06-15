<?php
session_start();
require('dbconnect.php');

if (!empty($_SESSION['id']) && !empty($_SESSION['id'] && $_SESSION['time'] + 3600 > time())) {
	$_SESSION['time'] = time();
	$members = $dbh->prepare('SELECT * FROM members WHERE id = ?');
	$members->execute(array($_SESSION['id']));
	$member = $members->fetch();
}
if (!empty($_POST) && $_POST['message'] !== '') {
	$message = $dbh->prepare('INSERT INTO posts SET member_id = ?, message = ?, reply_message_id = ?, created = NOW()');
	$message->execute(array(
		$member['id'],
		$_POST['message'],
		$_POST['reply_post_id']
	));
	header('Location: index.php');
	exit();
}
$page = 1;
if (!empty($_REQUEST['page'])) {
	$page = $_REQUEST['page'];
}
$page = max($page, 1);
$counts = $dbh->query('SELECT COUNT(*) AS cnt FROM posts WHERE is_deleted = 0');
$cnt = $counts->fetch();
$maxPage = ceil($cnt['cnt'] / 5);
$page = min($page, $maxPage);
$start = ($page - 1) * 5;
$posts = $dbh->prepare('SELECT m.name, m.user_file, p.* FROM members m, posts p WHERE m.id = p.member_id AND is_deleted = 0 ORDER BY p.created DESC LIMIT ?, 5');
$posts->bindParam(1, $start, PDO::PARAM_INT);
$posts->execute();
if (!empty($_REQUEST['res'])) {
	//返信の処理
	$response = $dbh->prepare('SELECT m.name, p.* FROM members m, posts p WHERE m.id = p.member_id AND p.id = ?');
	$response->execute(array($_REQUEST['res']));
	$table = $response->fetch();
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>掲示板</title>
<link rel="stylesheet" href="style.css" />
</head>
<body>
<div id="wrap">
<div id="head">
<h1>掲示板</h1>
</div>
<div id="content">
<form action="" method="post">
<dl>
<?php if (!empty($member['name'])) : ?>
<p>※"<?php print(htmlspecialchars($member['name'], ENT_QUOTES)); ?>"でログイン中</p>
<?php endif; ?>
<p>メッセージの投稿はこちらへ。</p>
<p>&raquo;<a href="post.php">メッセージを投稿する</a></p>
<table border="1" width="700" align="center" style="font-size : 16px;">
<tr>
<th width="7%">投稿ID</th>
<th width="11%">ユーザー<br>画像</th>
<th width="13%">投稿者</th>
<th width="20%">タイトル</th>
<th width="40%">本文</th>
<th width="20%">記入日</th>
</tr>
<?php foreach ($posts as $post) : ?>
<tr align="center">
<td>
<?php print(htmlspecialchars($post['id'], ENT_QUOTES)); ?>
</td>
<td>
<?php if (!empty($post['user_file'])) : ?>
<img src="member_picture/<?php print(htmlspecialchars($post['user_file'], ENT_QUOTES)); ?>" width="48" height="48"/>
<?php else : ?>
<p>未登録</p>
<?php endif; ?>
</td>
<td>
<?php print(htmlspecialchars($post['name'], ENT_QUOTES)); ?>
<div>
[<a href="user.php?id=<?php print(htmlspecialchars($post['id'], ENT_QUOTES)); ?>">詳細</a>]
</div>
</td>
<td>
<?php print(htmlspecialchars($post['title'], ENT_QUOTES)); ?>
<div>
<?php if (!empty($_SESSION['id']) && $_SESSION['id'] == $post['member_id']) : ?>
[<a href="edit.php?id=<?php print(htmlspecialchars($post['id'], ENT_QUOTES)); ?>">編集</a>]
[<a href="delete.php?id=<?php print(htmlspecialchars($post['id'], ENT_QUOTES)); ?>"style="color: #F33;">削除</a>]
<?php endif; ?>
</div>
</td>
<td><?php print(htmlspecialchars($post['message'], ENT_QUOTES)); ?></td>
<td><?php print(htmlspecialchars($post['created'], ENT_QUOTES)); ?></td>
</tr>
<?php endforeach; ?>
</table>
<?php if (!empty($member['name'])) : ?>
<p>ログアウトはこちらへ。</p>
<p>&raquo;<a href="logout.php">ログアウト</a></p>
<?php else : ?>
<p>ログインはこちらへ。</p>
<p>&raquo;<a href="login.php">ログインをする</a></p>
<?php endif; ?>
<ul class="paging">
<?php if ($page > 1) : ?>
<li><a href="index.php?page=<?php print($page - 1); ?>">前のページへ</a></li>
<?php else : ?>
<li>前のページへ</li>
<?php endif; ?>
<?php if ($page < $maxPage) : ?>
<li><a href="index.php?page=<?php print($page + 1); ?>">次のページへ</a></li>
<?php else : ?>
<li>次のページへ</li>
<?php endif; ?>
</ul>
</form>
</div>
</div>
</div>
</body>
</html>
