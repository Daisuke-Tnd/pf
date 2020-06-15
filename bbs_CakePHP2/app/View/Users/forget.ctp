<h1>Forgot Password</h1>
<?php
echo $this->Form->create('User');
echo $this->Form->input('email');
echo $this->Form->end('パスワードを再発行する');
