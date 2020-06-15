<h1>Reset Password</h1>
<h4>半角英数字かつ8文字以上で入力してください</h4>
<?php
echo $this->Form->create('User');
echo $this->Form->input('password');
echo $this->Form->end('パスワードを変更する');