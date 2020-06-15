<h1>Blog posts</h1>
<p>
<?php
echo $this->Html->link(
	'Add Post',
	array('action' => 'add')
);
?>
</p>
<?php if (!empty($user['username'])): ?>
<p><span style="background-color:#ffff66;">"<?php echo $user['username']; ?>"でログイン中</span></p>
<?php else: ?>
<p><span style="background-color:#ffff66;">ログインしていません</span></p>
<?php endif; ?>
<table border="1" width="700" align="center" style="font-size:16px;">
<tr>
<th width="7%">投稿ID</th>
<th width="7%">投稿画像</th>
<th width="13%">投稿者</th>
<th width="20%">タイトル</th>
<th width="25%">本文</th>
<th width="15%">削除・編集<br>(Login時のみ表示)</th>
<th width="15%">記入日</th>
</tr>
<?php arsort($posts); ?>
<?php foreach ($posts as $post): ?>
<tr align="center">
<td><?php echo $post['Post']['id']; ?></td>
<td>
<?php if (!empty($post['User']['user_file'])) : ?>
<img src="app/webroot/img/<?php echo h($post['User']['user_file']); ?>" width="48" height="48"/>
<?php else : ?>
<p>未登録</p>
<?php endif; ?>
</td>
<td>
<?php
echo $this->Html->link(
	$post['User']['username'],
	array('action' => 'view', $post['Post']['id'])
);
?>
</td>
<td><?php echo $post['Post']['title']; ?></td>
<td><?php echo $post['Post']['body']; ?></td>
<td>
<?php if (!empty($user['username']) && $user['username'] == $post['User']['username']): ?>
<?php
echo $this->Form->postLink(
	'Delete',
	array('action' => 'delete', $post['Post']['id']),
	array('confirm' => 'Are you sure?')
);
?>
&ensp;
<?php
echo $this->Html->link(
	'Edit',
	array('action' => 'edit', $post['Post']['id'])
);
?>
<?php endif; ?>
</td>
<td><?php echo $post['Post']['created']; ?></td>
</tr>
<?php endforeach; ?>
</table>
<?php if (empty($user['username'])): ?>
<p>
<?php
echo $this->Html->link(
	'Login',
	array('controller' => 'users', 'action' => 'login')
);
?>
</p>
<p>
<?php
echo $this->html->link(
	'新規登録',
	array('controller' => 'users', 'action' => 'add')
);
?>
</p>
<?php else: ?>
<p>
<?php
echo $this->html->link(
	'logout',
	array('controller' => 'users', 'action' => 'logout')
);
?>
</p>
<?php endif; ?>
