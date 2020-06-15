<h1>投稿者詳細</h1>
<?php if (!empty($user['username']) && $user['username'] == $post['User']['username']): ?>
<?php
echo $this->Html->link(
	'Edit',
	array('controller' => 'users', 'action' => 'edit', $post['Post']['user_id'])
);
?>
<?php endif; ?>
<table border="1" width="700" align="center" style="font-size : 16px;">
<tr>
<th width="5%">投稿者画像</th>
<th width="13%">投稿者名</th>
<th width="20%">メールアドレス</th>
<th width="20%">一言コメント</th>
<th width="40%">登録日</th>
</tr>
<tr align="center">
<td>
<?php if (!empty($post['User']['user_file'])) : ?>
<img src="../../app/webroot/img/<?php echo h($post['User']['user_file']); ?>" width="48" height="48"/>
<?php else : ?>
<p>未登録</p>
<?php endif; ?>
</td>
<td><?php echo h($post['User']['username']); ?></td>
<td><?php echo h($post['User']['email']); ?></td>
<td><?php echo h($post['User']['comment']); ?></td>
<td><?php echo h($post['User']['created']); ?></td>
</tr>
</table>